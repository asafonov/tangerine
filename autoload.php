<?php


function autoloader($classname){
    if (class_exists($classname)) {
        return false;
    } 
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
        require($filename);
    } else {
        $handle = opendir("{$docroot}/{$basedir}");
        $found = false;
        while (false!==($entry = readdir($handle))) {
            $filename="{$docroot}/{$basedir}/{$entry}/{$classname}.{$type}.php";
            if (file_exists($filename)) {
                require($filename);
                $found = true;
                break;
            }
        }
        closedir($handle);
        if (!$found) {
            echo "<pre>";
            debug_print_backtrace();
            die($filename);
        }
    }
}

spl_autoload_register('autoloader');
?>
