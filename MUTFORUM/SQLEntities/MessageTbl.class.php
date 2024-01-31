<?php
class MessageTbl
{
  private $attr = ["ID_MESSAGE" => '', "DISPLAY_" => '', "DATE_MESSAGE" => '', "CONTENT_MESSAGE" => '', "ID_USER" => '', "ID_SUJET" => ''];
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
  public function getUSer($sqlf)
  {
    SqlEntities::LoadEntity("utilisateur_tbl");
    return UtilisateurTbl::getUtilisateurTblBy($sqlf, "ID_USER", $this->ID_USER);
  }
  public function getTheme($sqlf)
  {
    SqlEntities::LoadEntity("sujet_tbl");
    return SujetTbl::getSujetTblBy($sqlf, "ID_SUJET", $this->ID_SUJET);
  }
  public function __get($name)
  {
    if (array_key_exists($name, $this->attr)) {
      return $this->attr[$name];
    } else {
      throw new Exception("Propriété non définie : $name");
    }
  }
  public static function  add(SQLFactoryV2 $sqlF, MessageTbl $item)
  {
    return $sqlF->addItem($item->getArray(), "message_tbl");
  }
  public static function  update(SQLFactoryV2 $sqlF, MessageTbl $item)
  {
    $sqlF->updateItem($item->getArray(), "message_tbl");
  }
  public static function  del(SQLFactoryV2 $sqlF, MessageTbl $item)
  {
    $sqlF->deleteItem($item->getArray(), "message_tbl");
  }
  public static function getAll($sqlF)
  {
    $query = $sqlF->execQuery("SELECT * FROM message_tbl");
    $return = [];
    foreach ($query as $element) {
      $entity = new MessageTbl();
      $entity->ID_MESSAGE = $element["ID_MESSAGE"];
      $entity->DATE_MESSAGE = $element["DATE_MESSAGE"];
      $entity->CONTENT_MESSAGE = $element["CONTENT_MESSAGE"];
      $entity->DISPLAY_ = $element["DISPLAY_"];
      $entity->ID_USER = $element["ID_USER"];
      $entity->ID_SUJET = $element["ID_SUJET"];
      $return[] = $entity;
    }
    return (count($return) > 1) ? $return : $return[0];
  }
  public function hasReponse($sqlF)
  {
    SqlEntities::LoadEntity("reponse_tbl");
    $r = ReponseTbl::getReponseTblBy($sqlF, "ID_MESSAGE", $this->ID_MESSAGE);
    return $r != null;
  }
  public function getReponses($sqlF)
  {
    SqlEntities::LoadEntity("reponse_tbl");
    $r = ReponseTbl::getReponseTblBy($sqlF, "ID_MESSAGE", $this->ID_MESSAGE);
    if (gettype($r) == "array")
      return $r;
    else
      return [$r];
  }
  public static function getMessageTblBy($sqlF, $key, $value)
  {
    $query = $sqlF->execQuery("SELECT * FROM message_tbl WHERE $key=$value");
    $return = [];
    foreach ($query as $element) {
      $entity = new MessageTbl();
      $entity->ID_MESSAGE = $element["ID_MESSAGE"];
      $entity->DATE_MESSAGE = $element["DATE_MESSAGE"];
      $entity->CONTENT_MESSAGE = $element["CONTENT_MESSAGE"];
      $entity->DISPLAY_ = $element["DISPLAY_"];
      $entity->ID_USER = $element["ID_USER"];
      $entity->ID_SUJET = $element["ID_SUJET"];
      $return[] = $entity;
    }
    return (count($return) > 1) ? $return : $return[0];
  }
}
