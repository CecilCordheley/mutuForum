<?php
class SujetTbl
{
  private $attr = ["ID_SUJET" => '', "LIB_SUJET" => '', "DATE_SUJET" => '', "ID_USER" => '', "ID_THEME" => ''];
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
  public static function  add(SQLFactoryV2 $sqlF, SujetTbl $item)
  {
    $sqlF->addItem($item->getArray(), "sujet_tbl");
  }
  public static function  update(SQLFactoryV2 $sqlF, SujetTbl $item)
  {
    $sqlF->updateItem($item->getArray(), "sujet_tbl");
  }
  public static function  del(SQLFactoryV2 $sqlF, SujetTbl $item)
  {
    $sqlF->deleteItem($item->getArray(), "sujet_tbl");
  }
  public static function getAll($sqlF)
  {
    $query = $sqlF->execQuery("SELECT * FROM sujet_tbl");
    $return = [];
    foreach ($query as $element) {
      $entity = new SujetTbl();
      $entity->ID_SUJET = $element["ID_SUJET"];
      $entity->LIB_SUJET = $element["LIB_SUJET"];
      $entity->DATE_SUJET = $element["DATE_SUJET"];
      $entity->ID_USER = $element["ID_USER"];
      $entity->ID_THEME = $element["ID_THEME"];
      $return[] = $entity;
    }
    return (count($return) > 1) ? $return : $return[0];
  }
  public static function getSujetTblBy($sqlF, $key, $value)
  {
    $query = $sqlF->execQuery("SELECT * FROM sujet_tbl WHERE $key=$value");
    $return = [];
    foreach ($query as $element) {
      $entity = new SujetTbl();
      $entity->ID_SUJET = $element["ID_SUJET"];
      $entity->LIB_SUJET = $element["LIB_SUJET"];
      $entity->DATE_SUJET = $element["DATE_SUJET"];
      $entity->ID_USER = $element["ID_USER"];
      $entity->ID_THEME = $element["ID_THEME"];
      $return[] = $entity;
    }
    return (count($return) > 1) ? $return : $return[0];
  }
}
