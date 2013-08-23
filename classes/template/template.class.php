<?php

class template {
    
    private $placeholders;
    private $name;
    private $template_connector;
    private $html;
    private $vars;
    private $default;

    const PHP_SHORT_OPEN = '<?';
    const PHP_CLOSE = '?>';

    
    public function __construct($name='') {
        if($name){
            $this->setName($name);
        }
        $this->template_connector = registry::getInstance()->getService('templateConnector');
    }
    
    public function getPlaceholders() {
        if(!$this->placeholders){
            $this->parseTemplate();
        }
        return $this->placeholders;
    }

    public function setPlaceholders($placeholders) {
        $this->placeholders = $placeholders;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
        $this->placeholders = null;
    }

    public function getHtml() {
        return $this->html;
    }

    public function setHtml($html) {
        $this->html = $html;
    }

    private function parseTemplate(){
        if(!$this->name){
            throw new RuntimeException("Template name is empty");
        } 
        $this->html=  $this->template_connector->get($this->name);
        if(!$this->placeholders){
            $matches=array();
            $regexp='/\{(?:if +){0,1}([A-z0-9_-]+)\}/sim';
            preg_match_all($regexp, $this->html, $matches);
            $this->placeholders = array_values(array_unique($matches[1]));
            $else_pos = array_search('else', $this->placeholders);
            if ($else_pos!==false) {
                unset($this->placeholders[$else_pos]);
            }
        }
    }

    public function clear() {
        $this->html = '';
        $this->vars = array();
    }

    public function fill($data=array(), $name='') {
        if ($name) {
            $this->setName($name);
            $this->clear();
        }
        if (!is_array($data)) {
            throw new RuntimeException("Template data should be an array");
        }
        foreach ($data as $k=>$v) {
            $this->vars[$k] = $v;
        }
        $include = $this->template_connector->getCompiled($this->name);
        if (!$include) {
            $this->compileTemplate();
            $include = $this->template_connector->getCompiled($this->name);
        }
        $spam = print_r($this->vars, true);
        $this->vars['_all_placeholders'] = $spam;
        ob_start();
        include($include);
        return ob_get_clean();
    }

    public function clearCache() {
        if(!$this->name) {
            throw new RuntimeException("Template name is empty");
        }
        $this->placeholders = null;
        $this->template_connector->deleteCompiled($this->name);
        $this->parseTemplate();
    }
    
    public function compileTemplate() {
        if(!$this->name) {
            throw new RuntimeException("Template name is empty");
        }
        $this->html=  $this->template_connector->get($this->name);
        if (!$this->placeholders) {
            $this->parseTemplate();
        } 

        $search = array('{/if}', '{else}');
        $replacement = array('<?php endif; ?>', '<?php else: ?>');

        foreach ($this->placeholders as $p) {
            $search[] = '{'.$p.'}';
            $search[] = '{if '.$p.'}';
            $replacement[] = '<?=$this->getVar(\''.$p.'\')?>';
            $replacement[] = '<?php if ($this->getVar(\''.$p.'\')): ?>';
        }

        $this->html = preg_replace('/<\?(.*?)\?>/sim', '<?=self::PHP_SHORT_OPEN?>\\1<?=self::PHP_CLOSE?>', $this->html);
        $this->html = str_replace($search, $replacement, $this->html);
        $this->html = preg_replace('/<!--.*?-->/sim', '', $this->html);
        $filename = $this->template_connector->save($this->name, $this->html, true);

        return true;
    }
    
    private function getVar($name){
        if(!is_scalar($name)){
            return "";
        } elseif (isset ($this->vars[$name])){
            return $this->vars[$name];
        } else {
            return '';
        }
    }
}
