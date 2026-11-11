"""Deploy only the reviewed SKS manifest; preserve all other production files."""
import argparse
import datetime
import fcntl
import hashlib
import json
import os
from pathlib import Path
import re
import shutil
import subprocess
import tempfile
import urllib.request


def digest(data):
    return hashlib.sha256(data).hexdigest()


def run():
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument('--source', default=str(Path(__file__).resolve().parents[1]))
    parser.add_argument('--dry-run', action='store_true')
    args = parser.parse_args()
    source = Path(args.source).resolve()
    spec = json.loads((source / 'tools/sks-deploy.json').read_text())
    root = Path(spec['root'])
    if not root.is_absolute() or root.resolve() != root or not root.is_dir():
        raise RuntimeError('Invalid production root')
    state = Path(spec['state'])
    if state.resolve().is_relative_to(root):
        raise RuntimeError('Backups must be outside document root')
    state.mkdir(parents=True, exist_ok=True)

    def http(path):
        request = urllib.request.Request('https://' + spec['domain'] + path,
                                         headers={'User-Agent': 'SKS-deploy-check', 'Cache-Control': 'no-cache'})
        with urllib.request.urlopen(request, timeout=20) as response:
            if response.status != 200:
                raise RuntimeError('HTTP check failed: ' + path)
            return response.read()

    with (state / 'deploy.lock').open('a') as lock:
        fcntl.flock(lock, fcntl.LOCK_EX | fcntl.LOCK_NB)
        history = state / 'current.json'
        previous = json.loads(history.read_text()) if history.exists() else {}
        files = []
        for entry in spec['files']:
            rel = entry['path']
            target = root / rel
            if Path(rel).is_absolute() or '..' in Path(rel).parts or target.resolve() != target:
                raise RuntimeError('Unsafe destination: ' + rel)
            data = (source / rel).read_bytes()
            current = target.read_bytes() if target.is_file() else None
            old_hash = digest(current) if current is not None else None
            allowed = entry['base_hashes'] + [digest(data)]
            if rel in previous.get('files', {}):
                allowed.append(previous['files'][rel])
            if old_hash not in allowed:
                raise RuntimeError('Production conflict; no files changed: ' + rel)
            if target.suffix == '.php':
                check = subprocess.run([spec.get('php', 'php'), '-l'], input=data, capture_output=True)
                if check.returncode:
                    raise RuntimeError('PHP syntax failed: ' + rel)
            files.append((rel, target, data, current, target.stat() if target.exists() else target.parent.stat() if target.parent.exists() else root.stat()))
        before = http('/')
        if args.dry_run:
            print(json.dumps({'status': 'preflight passed', 'files': len(files), 'domain': spec['domain']}))
            return
        stamp = datetime.datetime.now(datetime.timezone.utc).strftime('%Y%m%dT%H%M%S%fZ')
        backup = state / stamp
        backup.mkdir(mode=0o700)
        (backup / 'before.html').write_bytes(before)
        if history.exists():
            shutil.copy2(history, backup / 'previous.json')
        (backup / 'manifest.json').write_text(json.dumps(spec, indent=2))
        changed = []

        def write(target, data, meta):
            missing = []
            for parent in [target.parent, *target.parent.parents]:
                if parent.exists():
                    break
                missing.append(parent)
            target.parent.mkdir(parents=True, exist_ok=True)
            # Change ownership only on directories created by this deployment.
            for parent in missing:
                if os.geteuid() == 0:
                    os.chown(parent, meta.st_uid, meta.st_gid)
            fd, name = tempfile.mkstemp(prefix='.sks-', dir=target.parent)
            try:
                with os.fdopen(fd, 'wb') as handle:
                    handle.write(data)
                    handle.flush()
                    os.fsync(handle.fileno())
                os.chmod(name, (meta.st_mode & 0o777) if target.is_file() else 0o664)
                if os.geteuid() == 0:
                    os.chown(name, meta.st_uid, meta.st_gid)
                os.replace(name, target)
            finally:
                if os.path.exists(name):
                    os.unlink(name)

        try:
            # Assets first, then templates that reference them.
            for rel, target, data, current, meta in sorted(files, key=lambda row: not row[0].startswith('assets/')):
                if current == data:
                    continue
                if (target.read_bytes() if target.is_file() else None) != current:
                    raise RuntimeError('File changed after preflight: ' + rel)
                if current is not None:
                    copy = backup / 'files' / rel
                    copy.parent.mkdir(parents=True, exist_ok=True)
                    shutil.copy2(target, copy)
                changed.append((target, current, meta))
                write(target, data, meta)
            after = http('/')
            if after.count(b'id="exchange-links"') != 1 or after.count(b'?widget=js') != 1:
                raise RuntimeError('Homepage widget check failed')
            for tag in [b'title', b'h1']:
                pattern = b'<' + tag + b'[^>]*>(.*?)</' + tag + b'>'
                if re.findall(pattern, before, re.S) != re.findall(pattern, after, re.S):
                    raise RuntimeError('Existing page heading changed')
            for rel, _, data, _, _ in files:
                if rel.startswith('assets/') and http('/' + rel) != data:
                    raise RuntimeError('Published asset mismatch: ' + rel)
            revision = subprocess.run(['git', '-C', str(source), 'rev-parse', 'HEAD'], capture_output=True, text=True)
            result = {'status': 'deployed', 'revision': os.environ.get('GITHUB_SHA', revision.stdout.strip()), 'backup': str(backup),
                      'files': {rel: digest(data) for rel, _, data, _, _ in files}}
            temp = state / 'current.json.tmp'
            temp.write_text(json.dumps(result, indent=2))
            os.replace(temp, history)
            print(json.dumps(result))
        except Exception:
            for target, original, meta in reversed(changed):
                if original is None:
                    target.unlink(missing_ok=True)
                else:
                    write(target, original, meta)
            print('Publication failed; changed files restored from backup')
            raise


if __name__ == '__main__':
    run()
