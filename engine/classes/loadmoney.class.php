<?php

class LoadMoney
{
    private $config = array();
    private $errstr = 'Нет ошибок';
    private $fileTypes = array('Не указывать', 'audio', 'video', 'setup', 'book', 'torrent', 'archive', 'disk');
    private $typesExt = array('html', 'mp3', 'avi', 'exe', 'txt', 'torrent', 'zip', 'iso');

    private function setArray($config, $par)
    {
        if (!isset($config[$par]))
            $this->config[$par] = array();
        else
            $this->config[$par] = $config[$par];
    }

    private function setInt($config, $par)
    {
        if (!isset($config[$par]))
            $this->config[$par] = 0;
        else
            $this->config[$par] = intval($config[$par]);
    }

    private function setIntMax($config, $par, $max)
    {
        if (!isset($config[$par]))
            $this->config[$par] = 0;
        else {
            $num = intval($config[$par]);
            if ($num <= 0 or $num > $max)
                $this->config[$par] = 0;
            else
                $this->config[$par] = $num;
        }
    }

    private function setBool($config, $par)
    {
        if (isset($config[$par]) and $config[$par] === true)
            $this->config[$par] = true;
        else
            $this->config[$par] = false;
    }

    private function setString($config, $par)
    {
        if (isset($config[$par]))
            $this->config[$par] = $config[$par];
        else
            $this->config[$par] = '';
    }

    // loads configuration
    public function loadConfig()
    {
        if (is_file(loadmoney_conffile)) {
            include loadmoney_conffile;
        }
        // sid
        $this->setInt($config, 'sid');
        // hosts
        $this->setArray($config, 'hosts');
        // groups
        $this->setArray($config, 'groups');
        // state
        $this->setBool($config, 'on');
        // attachments
        $this->setBool($config, 'attach');
        // news
        $this->setBool($config, 'news');
        // static
        $this->setBool($config, 'static');
        // file name type
        $this->setBool($config, 'filenametype');
        // domain
        $this->setString($config, 'domain');
        // file name
        $this->setString($config, 'filename');
        // file type
        $this->setIntMax($config, 'filetype', count($this->fileTypes) - 1);
    }

    // saves configuration
    public function saveConfig($on, $sid, $hosts, $groups, $attach, $news, $static, $domain, $filetype, $filenametype, $filename)
    {
        // parsing int
        $psid = intval($sid);
        $pfiletype = intval($filetype);
        // parsing hosts
        $phosts = array();
        $tmp_hosts = explode("\n", $hosts);
        foreach ($tmp_hosts as $host)
            if (($tmp = trim($host)) != '')
                $phosts[] = str_replace('\'', '\\\'', str_replace('\\', '\\\\', $tmp));
        $phosts = array_unique($phosts);
        // parsing groups
        $pgroups = array();
        if (isset($groups))
            foreach ($groups as $group)
                $pgroups[] = intval($group);
        $pgroups = array_unique($pgroups);
        // parsing strings
        $pdomain = str_replace('\'', '\\\'', str_replace('\\', '\\\\', $domain));
        $pfilename = str_replace('\'', '\\\'', str_replace('\\', '\\\\', $filename));

        $str = '<?php

$config = array(
    \'on\' => ' . ($on ? 'true' : 'false') . ',
    \'attach\' => ' . ($attach ? 'true' : 'false') . ',
    \'news\' => ' . ($news ? 'true' : 'false') . ',
    \'static\' => ' . ($static ? 'true' : 'false') . ',
    \'filenametype\' => ' . ($filenametype ? 'true' : 'false') . ',
    \'sid\' => ' . $psid . ',
    \'hosts\' => array(';
        $tmp = '';
        foreach ($phosts as $host)
            $tmp .= '
        \'' . $host . '\',';
        $str .= substr($tmp, 0, -1) . '
    ),
    \'groups\' => array(';
        $tmp = '';
        foreach ($pgroups as $group)
            $tmp .= '
        ' . $group . ',';
        $str .= substr($tmp, 0, -1) . '
    ),
    \'domain\' => \'' . $pdomain . '\',
    \'filetype\' => ' . $pfiletype . ',
    \'filename\' => \'' . $pfilename . '\'
);';
        if (false === file_put_contents(loadmoney_conffile, $str)) {
            $this->errstr = 'Не могу записать конфигурацию в файл "' . realpath(loadmoney_conffile) . '"';
            return false;
        }
        return true;
    }

    // return whether loadmoney replacement allowed or not
    public function isAllowed($url, $group, $isattach = false)
    {
        if (!isset($this->config))
            return false;
        if (!$this->config['on'])
            return false;
        if (!$isattach and count($this->config['hosts'])) {
            if (false === preg_match('@^(?>https?://)?(.*?)(?>/|$)@', $url, $matches)) {
                return false;
            }
            if (!in_array($matches[1], $this->config['hosts']))
                return false;
        }
        if (!in_array($group, $this->config['groups']))
            return false;
        return true;
    }

    // returns loadmoney url
    public function createUrl($url, $group, $name = null, $size = null, $isattach = false)
    {
        if (!$this->isAllowed($url, $group, $isattach))
            return '';
        $str = 'class="download_me" download_url="' . base64_encode($url) . '" ';
        if (isset($name))
            $str .= 'download_name="' . base64_encode($name) . '" ';
        else {
            if ($this->config['filenametype'] and preg_match('@/(?!.*/)\d*-*(.*)\.html@', $_SERVER['REQUEST_URI'], $matches) and $matches[1]) {
                $tmpName = $matches[1];
                if ($this->config['filetype'])
                    $tmpName .= '.' . $this->typesExt[$this->config['filetype']];
            } else
                $tmpName = $this->config['filename'];
            if ($tmpName)
                $str .= 'download_name="' . base64_encode($tmpName) . '" ';
        }
        if ($this->config['filetype'])
            $str .= 'download_type="' . base64_encode($this->fileTypes[$this->config['filetype']]) . '" ';
        if (isset($size))
            $str .= 'download_size="' . base64_encode($size) . '" ';
        return $str;
    }

    // returns error string
    public function error()
    {
        return $this->errstr;
    }

    // return list of available file types
    public function getFileTypes()
    {
        return $this->fileTypes;
    }

    // returns sid
    public function getSid()
    {
        return $this->config['sid'];
    }

    // returns file type
    public function getFileType()
    {
        return $this->config['filetype'];
    }

    // returns hosts
    public function getHosts()
    {
        return $this->config['hosts'];
    }

    // returns groups
    public function getGroups()
    {
        return $this->config['groups'];
    }

    // returns domain
    public function getDomain()
    {
        return $this->config['domain'];
    }

    // returns real domain
    public function getRealDomain()
    {
        return $this->config['domain'] ? $this->config['domain'] : 'loadmoney.ru';
    }

    // returns file name
    public function getFileName()
    {
        return $this->config['filename'];
    }

    // returns state
    public function isOn()
    {
        return $this->config['on'];
    }

    // returns attachments
    public function isAttachments()
    {
        return $this->config['attach'];
    }

    // returns news
    public function isNews()
    {
        return $this->config['news'];
    }

    // returns static
    public function isStatic()
    {
        return $this->config['static'];
    }

    // returns file name type
    public function isFileNameType()
    {
        return $this->config['filenametype'];
    }

    public function replaceLinks($str, $group)
    {
        $resstr = '';
        $pos2 = 0;
        while (false !== $pos = strpos($str, 'href="', $pos2)) {
            $resstr .= substr($str, $pos2, $pos - $pos2);
            if (false === $pos2 = strpos($str, '"', $pos + 6)) {
                $pos2 = $pos;
                break;
            }
            $url = substr($str, $pos + 6, $pos2 - $pos - 6);
            $resstr .= $this->createUrl($url, $group) . substr($str, $pos, $pos2 - $pos);
        }
        $resstr .= substr($str, $pos2);
        return $resstr;
    }

    static public function strToJSArray($str){
        $res='[';
        for($i=strlen($str)-1;$i>=0;$i--)
            $res.=ord($str[$i]).',';
        return substr($res,0,-1).']';
    }
}
