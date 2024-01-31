<?php
 class StateuserTbl{
    private $attr=["ID_STATE"=>'',"LIBELLE_STATE"=>''];
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
    public static function  add(SQLFactoryV2 $sqlF,StateuserTbl $item){
      $sqlF->addItem($item->getArray(),"stateuser_tbl");
    }
    public static function  update(SQLFactoryV2 $sqlF,StateuserTbl $item){
      $sqlF->updateItem($item->getArray(),"stateuser_tbl");
    }
    public static function  del(SQLFactoryV2 $sqlF,StateuserTbl $item){
      $sqlF->deleteItem($item->getArray(),"stateuser_tbl");
    }
    public static function getAll($sqlF){
      $query=$sqlF->execQuery("SELECT * FROM stateuser_tbl");
      $return=[];
      foreach($query as $element){
      $entity=new StateuserTbl();
         $entity->ID_STATE=$element["ID_STATE"];
$entity->LIBELLE_STATE=$element["LIBELLE_STATE"];
      $return[]=$entity;
      }
     return (count($return)>1)?$return:$return[0];
    }
    public static function getStateuserTblBy($sqlF,$key,$value){
      $query=$sqlF->execQuery("SELECT * FROM stateuser_tbl WHERE $key=$value");
      $return=[];
      foreach($query as $element){
      $entity=new StateuserTbl();
         $entity->ID_STATE=$element["ID_STATE"];
$entity->LIBELLE_STATE=$element["LIBELLE_STATE"];
      $return[]=$entity;
      }
     return (count($return)>1)?$return:$return[0];
    }
 }