# SKS publication

Run `python3 tools/deploy-sks.py --dry-run` on the production host from a checkout of the intended commit. Then run without `--dry-run`.

Only files listed in `sks-deploy.json` are published. Their live SHA-256 must match the reviewed baseline, the last successful publication, or the intended source. Any other change aborts before publication. PHP is linted first; original files are backed up outside the document root. A failed HTTP or asset check rolls back changed files. No database migration, Git reset, Git clean, configuration replacement, or CMS core update is performed.

The server Git HEAD deliberately stays unchanged: the publication record is `current.json` in the manifest state directory. Existing runtime edits must be reconciled before using any older full-site deployment script. The SKS workflow publishes only this manifest and preserves those edits. For subsequent changes, update the manifest if its file list changes.
