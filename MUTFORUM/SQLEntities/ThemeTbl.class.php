<?php
 class ThemeTbl{
    private $attr=["ID_THEME"=>'',"LIB_THEME"=>''];
    public function __set($name,$value){
      if (array_key_exists($name, $this->attr)) {
         $this->attr[$name]=$value;
     } else {
         throw new Exception("Propriété non définie : $name");
     }
    }
    public function getArray(){
      return $this->attr;
    }
    public function __get($name){
      if (array_key_exists($name, $this->attr)) {
         return $this->attr[$name];
     } else {
         throw new Exception("Propriété non définie : $name");
     }
    }
    public static function  add(SQLFactoryV2 $sqlF,ThemeTbl $item){
      $sqlF->addItem($item->getArray(),"theme_tbl");
    }
    public static function  update(SQLFactoryV2 $sqlF,ThemeTbl $item){
      $sqlF->updateItem($item->getArray(),"theme_tbl");
    }
    public static function  del(SQLFactoryV2 $sqlF,ThemeTbl $item){
      $sqlF->deleteItem($item->getArray(),"theme_tbl");
    }
    public static function getAll($sqlF){
      $query=$sqlF->execQuery("SELECT * FROM theme_tbl");
      $return=[];
      foreach($query as $element){
      $entity=new ThemeTbl();
         $entity->ID_THEME=$element["ID_THEME"];
$entity->LIB_THEME=$element["LIB_THEME"];
      $return[]=$entity;
      }
     return (count($return)>1)?$return:$return[0];
    }
    public static function getThemeTblBy($sqlF,$key,$value){
      $query=$sqlF->execQuery("SELECT * FROM theme_tbl WHERE $key=$value");
      $return=[];
      foreach($query as $element){
      $entity=new ThemeTbl();
         $entity->ID_THEME=$element["ID_THEME"];
$entity->LIB_THEME=$element["LIB_THEME"];
      $return[]=$entity;
      }
     return (count($return)>1)?$return:$return[0];
    }
 }