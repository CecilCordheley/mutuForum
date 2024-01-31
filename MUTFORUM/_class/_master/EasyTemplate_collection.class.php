<?php
/**
 * permet de gérer les variable du moteur de template
 * @author Cecil Cordheley
 */
class EasyTemplate_variable {
    /**
     *
     * @var array
     */
    private $array;
    
    public function __construct(){
        $this->array=array();
    }
   
    public function __set($name,$value){
        $this->array['var'][$name]=$value;
    }
    public function __get($key){
        if(isset($this->array['var'][$key])){
            return $this->array['var'][$key];
        }else
            return null;
    }
}
class EasyTemplate_subtemplate{
    /**
     *
     * @var array
     */
    private $array;
    
    public function __construct(){
        $this->array=array();
    }
   
    public function __set($name,$value){
        $this->array['stpl'][$name]=$value;
    }
    public function __get($key){
        if(isset($this->array['stpl'][$key])){
            return $this->array['stpl'][$key];
        }else
            return null;
    }
}
?>
