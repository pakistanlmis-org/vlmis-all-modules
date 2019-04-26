<?php

/* File Class */

class App_File {

    private $_path;
    private $_handle;
    private $_mode;
    private $_directory;
    private $_gettable = array(
        "path",
        "handle",
        "mode",
        "contents"
    );
    private $_settable = array(
        "path",
        "mode",
        "contents"
    );
    private $_modes = array(
        "read" => "r", //read
        "read/write" => "r+", //read and write, pointer at start of file
        "write" => "w", //write only, pointer at start of file
        "truncate" => "w+", //write only, pointer at start of file, truncate file
        "append" => "a", //write only, pointer at end of file
        "read/append" => "a+", //read and write, pointer at end of file
        "x" => "x", //write only, pointer at start of file
        "x+" => "x+" //read and write, pointer at beginning of file
    );
    private $_windows = array(
        "WIN32",
        "WINNT",
        "Windows"
    );

    /**
     * __construct function.
     * 
     * @access public
     * @param mixed $vars. (default: null)
     * @return void
     */
    public function __construct($vars = null) {
        if (!is_null($vars) && is_string($vars)) {
            $this->_path = $this->_fixDirectorySeparator($vars);
        } elseif (!is_null($vars) && is_array($vars)) {
            foreach ($vars as $key => $value) {
                if (in_array($key, $this->_settable)) {
                    $key = "_" . $key;
                    $this->$key = $value;
                }
            }
        } else {
            $this->setPath($_SERVER["SCRIPT_FILENAME"]);
            $this->setDirectory(dirname($this->_path));
            $this->setMode("r");
        }
    }

    /**
     * __get function.
     * 
     * @access public
     * @param string $key
     * @return mixed
     */
    public function __get($key) {
        if (in_array($key, $this->_gettable)) {
            switch ($key) {
                case "path":
                    return $this->_path;
                    break;
                case "name":
                    return $this->getName();
                    break;
                case "directory":
                    return $this->getDirectory();
                    break;
                case "handle":
                    return $this->open();
                    break;
                case "contents":
                    return $this->getContents();
                    break;
                default:
                    return false;
                    break;
            }
        }
    }

    /**
     * __set function.
     * 
     * @access public
     * @param string $key
     * @param string $value
     * @return Zend_File
     */
    public function __set($key, $value) {
        if (in_array($key, $this->_settable)) {
            switch ($key) {
                case "path":
                    $this->setPath($value);
                    break;
                case "mode":
                    $this->setMode($value);
                    break;
                default:
                    break;
            }

            return $this;
        } else {
            return false;
        }
    }

    /**
     * __toString function.
     * 
     * @access public
     * @return string Path
     */
    public function __toString() {
        return $this->_path;
    }

    /**
     * _fixDirectorySeparator function.
     * 
     * @access private
     * @param mixed $path
     * @return string Path
     */
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

    /**
     * setMode function.
     * 
     * @access public
     * @param string $permissions
     * @return Zend_File
     */
    public function setMode($permissions) {
        if (!isset($permissions) || !array_key_exists($permissions, $this->_modes)) {
            return false;
        } else {
            $this->_mode = $this->_modes[$permissions];
            return $this;
        }
    }

    /**
     * getMode function.
     * 
     * @access public
     * @return string Mode
     */
    public function getMode() {
        return $this->_mode;
    }

    /**
     * setPath function.
     * 
     * @access public
     * @param string $path. (default: null)
     * @return Zend_File
     */
    public function setPath($path = null) {
        if (is_null($path) || !is_string($path)) {
            return false;
        } elseif (!is_file($this->_fixDirectorySeparator($path))) {
            return false;
        } else {
            $path = $this->_fixDirectorySeparator($path);
            $this->setDirectory(dirname($path));
            return $this;
        }
    }

    /**
     * getPath function.
     * 
     * @access public
     * @return string Path
     */
    public function getPath() {
        return $this->_path;
    }

    /**
     * getDirectory function.
     * 
     * @access public
     * @return string Directory
     */
    public function getDirectory() {
        if (!isset($this->_directory) && isset($this->_path)) {
            $this->_directory = dirname($this->_path);
        } elseif (!isset($this->_path)) {
            return false;
        }

        return $this->_directory;
    }

    /**
     * getName function.
     * 
     * @access public
     * @return string Name
     */
    public function getName() {
        return basename($this->_path);
    }

    /**
     * delete function.
     * 
     * @access public
     * @return Zend_File
     */
    public function delete() {
        if ($this->exists()) {
            unlink($this->_path);
            return $this;
        } else {
            return false;
        }
    }

    /**
     * open function.
     * 
     * @access public
     * @return Zend_File
     */
    public function open() {
        if (!$this->exists()) {
            return false;
        } else {
            $this->_handle = fopen($this->_path, $this->getMode());
            return $this;
        }
    }

    public function getHandle() {
        if (!is_null($this->_handle)) {
            return $this->_handle;
        } else {
            return false;
        }
    }

    /**
     * isOpen function.
     * 
     * @access public
     * @return bool Open
     */
    public function isOpen() {
        if (isset($this->_handle)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * isFile function.
     * 
     * @access public
     * @return bool is File
     */
    public function isFile() {
        return (isset($this->_path) && is_file($this->_path));
    }

    /**
     * exists function.
     * 
     * @access public
     * @return bool Exists
     */
    public function exists() {
        if (isset($this->_path) && file_exists($this->_path)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * close function.
     * 
     * @access public
     * @return Zend_File
     */
    public function close() {
        if ($this->isOpen()) {
            fclose($this->_path);
            $this->_handle = null;
        }

        return $this;
    }

    /**
     * move function.
     * 
     * @access public
     * @param string $path
     * @return Zend_File
     */
    public function move($path) {
        if (is_null($path)) {
            return false;
        } else {
            if ($this->isOpen()) {
                $this->close();
            }

            $path = $this->_fixDirectorySeparator($path);
            rename($this->_path, $path);

            return $this;
        }
    }

    /**
     * moveUpload function.
     * 
     * @access public
     * @param string $path
     * @return Zend_File
     */
    public function moveUpload($path) {
        if (is_null($path) && is_null($this->_path)) {
            return false;
        } else {
            $path = $this->_fixDirectorySeparator($path);
            move_uploaded_file($this->_path, $path);

            return $this;
        }
    }

    /**
     * copy function.
     * 
     * @access public
     * @param string $path
     * @return Zend_File
     */
    public function copy($path) {
        if (is_null($path) || !is_dir($path)) {
            return false;
        } else {
            $this->close();
            copy($this->_path, $path);

            return $this;
        }
    }

    /**
     * getContents function.
     * 
     * @access public
     * @return string Contents
     */
    public function getContents() {
        return file_get_contents($this->_path);
    }

    /**
     * read function.
     * 
     * @access public
     * @param mixed $length
     * @return string Data
     */
    public function read($length) {
        if (!is_numeric($length)) {
            return false;
        } else {
            if (!$this->isOpen()) {
                $this->open();
            }

            return fread($this->getHandle(), $length);
        }
    }

    /**
     * readLine function.
     * 
     * @access public
     * @return string Data
     */
    public function readLine() {
        if (!$this->isOpen()) {
            $this->open();
        }

        return fgets($this->getHandle());
    }

    /**
     * readCSV function.
     * 
     * @access public
     * @return string Data
     */
    public function readCSV() {
        if (!$this->isOpen()) {
            $this->open();
        }

        return fgetcsv($this->getHandle());
    }

    /**
     * isWritable function.
     * 
     * @access public
     * @return bool Writable
     */
    public function isWritable() {
        return is_writable($this->_path);
    }

    /**
     * write function.
     * 
     * @access public
     * @param string $data
     * @return Zend_File
     */
    public function write($data) {
        if (!isset($data) || !is_string($data) || !$this->isWritable()) {
            return false;
        } else {
            if (!$this->isOpen()) {
                $this->open();
            }

            fwrite($this->getHandle(), $data);

            return $this;
        }
    }

    public function download($file) {
        header("Content-Type: application/octet-stream");

        header("Content-Disposition: attachment; filename=" . urlencode($file));
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header("Content-Description: File Transfer");
        header("Content-Length: " . filesize($file));
        flush(); // this doesn't really matter.
        $fp = fopen($file, "r");
        while (!feof($fp)) {
            echo fread($fp, 65536);
            flush(); // this is essential for large downloads
        }
        fclose($fp);
        
        return $this;
    }

}

?>