<?php
 class TypeUtilisateur{
    private $attr=["idType_Utilisateur"=>'',"LIBELLE_Type_Utilisateur"=>''];
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
    public static function  add(SQLFactoryV2 $sqlF,TypeUtilisateur $item){
      $sqlF->addItem($item->getArray(),"type_utilisateur");
    }
    public static function  update(SQLFactoryV2 $sqlF,TypeUtilisateur $item){
      $sqlF->updateItem($item->getArray(),"type_utilisateur");
    }
    public static function  del(SQLFactoryV2 $sqlF,TypeUtilisateur $item){
      $sqlF->deleteItem($item->getArray(),"type_utilisateur");
    }
    public static function getAll($sqlF){
      $query=$sqlF->execQuery("SELECT * FROM type_utilisateur");
      $return=[];
      foreach($query as $element){
      $entity=new TypeUtilisateur();
         $entity->idType_Utilisateur=$element["idType_Utilisateur"];
$entity->LIBELLE_Type_Utilisateur=$element["LIBELLE_Type_Utilisateur"];
      $return[]=$entity;
      }
     return (count($return)>1)?$return:$return[0];
    }
    public static function getTypeUtilisateurBy($sqlF,$key,$value){
      $query=$sqlF->execQuery("SELECT * FROM type_utilisateur WHERE $key=$value");
      $return=[];
      foreach($query as $element){
      $entity=new TypeUtilisateur();
         $entity->idType_Utilisateur=$element["idType_Utilisateur"];
$entity->LIBELLE_Type_Utilisateur=$element["LIBELLE_Type_Utilisateur"];
      $return[]=$entity;
      }
     return (count($return)>1)?$return:$return[0];
    }
 }