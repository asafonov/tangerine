<?php

function _getClassFilename($classname) {
    $classname=  str_replace('_', '/', $classname);
    $docroot=$_SERVER['DOCUMENT_ROOT']?$_SERVER['DOCUMENT_ROOT']:$_SERVER['PWD'];
    if(strpos($classname, 'Interface')){
        $classname=  str_replace('Interface', '', $classname);
        $basedir='interfaces';
        $type='interface';
    }
    else{
        $basedir='classes';
        $type='class';
    }
    $filename="{$docroot}/{$basedir}/{$classname}.{$type}.php";
    if (file_exists($filename)) {
        return $filename;
    } else {
        $handle = opendir("{$docroot}/{$basedir}");
        $found = false;
        while (false!==($entry = readdir($handle))) {
            $filename="{$docroot}/{$basedir}/{$entry}/{$classname}.{$type}.php";
            if (file_exists($filename)) {
                return $filename;
                $found = true;
                break;
            }
        }
        closedir($handle);
        if (!$found) {
            return false;
        }
    }

}

function autoloader($classname){
    if (class_exists($classname)) {
        return false;
    }
    $filename = _getClassFilename($classname);
    if ($filename) {
        require($filename);
    } else {
        throw new RuntimeException("Class not found: $classname");
    }
}

function tangerineClassExists($classname) {
    return _getClassFilename($classname);
}

spl_autoload_register('autoloader');
?>
