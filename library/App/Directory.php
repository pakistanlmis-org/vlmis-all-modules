<?php

/* Directory Class */

class App_Directory {

    private $_path;
    private $_handle;
    private $_filelist = array();
    private $_dirlist = array();
    private $_gettable = array(
        "path",
        "handle",
        "files",
        "directories"
    );
    private $_settable = array(
        "path"
    );
    private $_windows = array(
        "WIN32",
        "WINNT",
        "Windows"
    );

    public function __construct($path = null) {
        if (!is_null($path)) {
            $this->_path = $this->_fixDirectorySeparator($path);
        } else {
            $this->_path = dirname($_SERVER["SCRIPT_FILENAME"]);
        }
    }

    public function getFiles($refresh = false) {
        $this->_handle = opendir($this->_path);

        if (count($this->_filelist) == 0 || $refresh) {
            while (false !== ($file = readdir($this->_handle))) {
                if ($file != "." && $file != ".." && !is_dir($this->_fixDirectorySeparator($this->_path . "/$file"))) {
                    $this->_filelist[] = $file;
                }
            }
        }

        return $this->_filelist;
    }

    public function getDirectories($refresh = false) {
        $this->_handle = opendir($this->_path);

        if (count($this->_dirlist) == 0 || $refresh) {
            while (false !== ($dir = readdir($this->_handle))) {
                if (is_dir($dir)) {
                    $this->_dirlist[] = $dir;
                }
            }
        } else {
            return $this->_dirlist;
        }
    }

    public function create($path = null) {
        if (is_null($path)) {
            mkdir($this->_path);
        } else {
            mkdir($path);
        }
    }

    public function __get($key) {
        if (in_array($key, $this->_gettable)) {
            switch ($key) {
                case "path":
                    return $this->_path;
                    break;
                case "files":
                    return $this->getFiles();
                    break;
                case "directories":
                    return $this->getDirectories();
                    break;
                case "handle":
                    $this->_handle = opendir($this->_path);
                    return $this->_handle;
                    break;
                default:
                    return false;
                    break;
            }
        }
    }

    public function isDirectory() {
        return is_dir($this->_path);
    }

    public function exists() {
        return file_exists($this->_path);
    }

    public function __set($key, $value) {
        if (in_array($key, $this->_settable)) {
            if ($key == "path") {
                $value = $this->_fixDirectorySeparator($value);
            }

            $key = "_" . $key;
            $this->$key = $value;
            return $this;
        } else {
            return false;
        }
    }

    public function __toString() {
        return $this->_path;
    }

    private function _fixDirectorySeparator($path) {
        if (!is_null($path)) {
            if (in_array(PHP_OS, $this->_windows)) {
                $path = str_replace("/", "\\", $path);
                return $path;
            } else {
                $path = str_replace("\\", "/", $path);
                return $path;
            }
        } else {
            return false;
        }
    }

}

?>