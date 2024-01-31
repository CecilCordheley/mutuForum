<?php
class ReponseTbl
{
  private $attr = ["ID_MESSAGE" => '',"DISPLAY"=>"", "ID_USER" => '', "CONTENT_REPONSE" => '', "DATE_REPONSE" => ''];
  public function __set($name, $value)
  {
    if (array_key_exists($name, $this->attr)) {
      $this->attr[$name] = $value;
    } else {
      throw new Exception("Propriété non définie : $name");
    }
  }
  public function getArray()
  {
    return $this->attr;
  }
  public function __get($name)
  {
    if (array_key_exists($name, $this->attr)) {
      return $this->attr[$name];
    } else {
      throw new Exception("Propriété non définie : $name");
    }
  }
  public static function  add(SQLFactoryV2 $sqlF, ReponseTbl $item)
  {
    $sqlF->addItem($item->getArray(), "reponse_tbl");
  }
  public static function  update(SQLFactoryV2 $sqlF, ReponseTbl $item)
  {
    $sqlF->updateItem($item->getArray(), "reponse_tbl");
  }
  public static function  del(SQLFactoryV2 $sqlF, ReponseTbl $item)
  {
    $sqlF->deleteItem($item->getArray(), "reponse_tbl");
  }
  public static function getAll($sqlF)
  {
    $query = $sqlF->execQuery("SELECT * FROM reponse_tbl");
    $return = [];
    foreach ($query as $element) {
      $entity = new ReponseTbl();
      $entity->ID_MESSAGE = $element["ID_MESSAGE"];
      $entity->ID_USER = $element["ID_USER"];
      $entity->CONTENT_REPONSE = $element["CONTENT_REPONSE"];
      $entity->DATE_REPONSE = $element["DATE_REPONSE"];
      $entity->DISPLAY = $element["DISPLAY"];
      $return[] = $entity;
    }
    return (count($return) > 1) ? $return : $return[0];
  }
  public static function findReponse($sqlF,$user,$message,$date){
    $query = $sqlF->execQuery("SELECT * FROM reponse_tbl WHERE ID_MESSAGE=$message AND ID_USER=$user AND DATE_REPONSE=$date");
    $return = [];
    foreach ($query as $element) {
      $entity = new ReponseTbl();
      $entity->ID_MESSAGE = $element["ID_MESSAGE"];
      $entity->ID_USER = $element["ID_USER"];
      $entity->CONTENT_REPONSE = $element["CONTENT_REPONSE"];
      $entity->DATE_REPONSE = $element["DATE_REPONSE"];
      $entity->DISPLAY = $element["DISPLAY"];
      $return[] = $entity;
    }
    if(count($return))
      return (count($return) > 1) ? $return : $return[0];
    else
      return [];
  }
  public static function getReponseTblBy($sqlF, $key, $value)
  {
    $query = $sqlF->execQuery("SELECT * FROM reponse_tbl WHERE $key=$value");
    $return = [];
    foreach ($query as $element) {
      $entity = new ReponseTbl();
      $entity->ID_MESSAGE = $element["ID_MESSAGE"];
      $entity->ID_USER = $element["ID_USER"];
      $entity->CONTENT_REPONSE = $element["CONTENT_REPONSE"];
      $entity->DATE_REPONSE = $element["DATE_REPONSE"];
      $entity->DISPLAY = $element["DISPLAY"];
      $return[] = $entity;
    }
    if(count($return))
      return (count($return) > 1) ? $return : $return[0];
    else
      return [];
  }
}
