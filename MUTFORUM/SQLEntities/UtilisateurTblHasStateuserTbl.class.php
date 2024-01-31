<?php
class UtilisateurTblHasStateuserTbl
{
  private $attr = ["USER" => '', "STATE" => '', "DATE_STATE" => '', "MOTIF_STATE" => ''];
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
  public static function  add(SQLFactoryV2 $sqlF, UtilisateurTblHasStateuserTbl $item)
  {
    $sqlF->addItem($item->getArray(), "utilisateur_tbl_has_stateuser_tbl");
  }
  public static function  update(SQLFactoryV2 $sqlF, UtilisateurTblHasStateuserTbl $item)
  {
    $sqlF->updateItem($item->getArray(), "utilisateur_tbl_has_stateuser_tbl");
  }
  public static function  del(SQLFactoryV2 $sqlF, UtilisateurTblHasStateuserTbl $item)
  {
    $sqlF->deleteItem($item->getArray(), "utilisateur_tbl_has_stateuser_tbl");
  }
  public static function getAll($sqlF)
  {
    $query = $sqlF->execQuery("SELECT * FROM utilisateur_tbl_has_stateuser_tbl");
    $return = [];
    foreach ($query as $element) {
      $entity = new UtilisateurTblHasStateuserTbl();
      $entity->USER = $element["USER"];
      $entity->STATE = $element["STATE"];
      $entity->DATE_STATE = $element["DATE_STATE"];
      $entity->MOTIF_STATE = $element["MOTIF_STATE"];
      $return[] = $entity;
    }
    return (count($return) > 1) ? $return : $return[0];
  }
  public static function getUtilisateurTblHasStateuserTblBy($sqlF, $key, $value)
  {
    $query = $sqlF->execQuery("SELECT * FROM utilisateur_tbl_has_stateuser_tbl WHERE $key=$value");
    $return = [];
    foreach ($query as $element) {
      $entity = new UtilisateurTblHasStateuserTbl();
      $entity->USER = $element["USER"];
      $entity->STATE = $element["STATE"];
      $entity->DATE_STATE = $element["DATE_STATE"];
      $entity->MOTIF_STATE = $element["MOTIF_STATE"];
      $return[] = $entity;
    }
    return (count($return) > 1) ? $return : $return[0];
  }
}
