<?php

class templateConnector {
    
    private $extension='.tpl';
    private $base='/templates/';
    private $compiled_base='/templates/compiled/';
    private $template_root = '';

    public function __construct() {
    }

    public function init($data) {
        foreach($data as $k=>$v) {
            if (property_exists($this, $k)) {
                $this->$k = $v;
            }
        }
    }

    public function get($template) {
        $source = $this->getSource($template);
        if ($source) {
            return $source;
        }
        throw new RuntimeException('Template doesn\'t exist '.$template);
    }

    public function getSource($template) {
        if(!$template){
            throw new RuntimeException('Template name is empty');
        } elseif (file_exists($_SERVER['DOCUMENT_ROOT'].$this->base.$template.'.tpl')) {
            return file_get_contents($_SERVER['DOCUMENT_ROOT'].$this->base.$template.'.tpl');
        } elseif (file_exists($this->template_root.$this->base.$template.'.tpl')) {
            return file_get_contents($this->template_root.$this->base.$template.'.tpl');
        } else {
            return false;
        }
    }

    public function deleteCompiled($template) {
        if(!$template){
            throw new RuntimeException('Template name is empty');
        } else {
            @unlink($_SERVER['DOCUMENT_ROOT'].$this->compiled_base.$template.'.php');
        }
    }

    public function getCompiled($template) {
        if(!$template){
            throw new RuntimeException('Template name is empty');
        } elseif (file_exists($_SERVER['DOCUMENT_ROOT'].$this->compiled_base.$template.'.php')) {
            return $_SERVER['DOCUMENT_ROOT'].$this->compiled_base.$template.'.php';
        } else {
            return false;
        }
    }

    public function save($template, $data) {
        if(!$template){
            throw new RuntimeException('Template name is empty');
        } elseif (!$data) {
            throw new RuntimeException('Template body is empty');
        } else {
            $directory = $_SERVER['DOCUMENT_ROOT'].$this->compiled_base;
            if (strpos($template, '/')!==false) {
                $directory .= implode('/', array_slice(explode('/', $template), 0, count(explode('/', $template))-1));
            }
            if (!file_exists($directory)) {
                if (!mkdir($directory, 0755, true)) {
                    throw new Exception('Can not make directory: '.$directory);
                }
            }
            if (!file_put_contents($_SERVER['DOCUMENT_ROOT'].$this->compiled_base.$template.'.php', $data)) {
                throw new Exception('Can not create file: '.$template.'.php');
            }
            return $_SERVER['DOCUMENT_ROOT'].$this->compiled_base.$template.'.php';
        }
    }
}
?>
