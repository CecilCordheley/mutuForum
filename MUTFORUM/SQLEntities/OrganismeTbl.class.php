<?php
class OrganismeTbl
{
  private $attr = ["ID_ORGA" => '', "NOM_ORGANISATION" => '', "DATE_ORGANISME" => '', "INFO_MUTUELLE" => '', "LOGO_ORGANISME" => '', "ALLOWED_ORGA" => ''];
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
  public static function  add(SQLFactoryV2 $sqlF, OrganismeTbl $item)
  {
    $sqlF->addItem($item->getArray(), "organisme_tbl");
  }
  public static function  update(SQLFactoryV2 $sqlF, OrganismeTbl $item)
  {
    $sqlF->updateItem($item->getArray(), "organisme_tbl");
  }
  public function getMessages($sqlF)
  {
    $query = $sqlF->execQuery("SELECT * FROM message_tbl m INNER JOIN utilisateur_tbl u ON m.ID_USER=u.ID_USER WHERE u.ID_ORGA=" . $this->ID_ORGA);
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
    if (count($return))
      return (count($return) > 1) ? $return : $return[0];
    else
      return false;
  }
  public static function  del(SQLFactoryV2 $sqlF, OrganismeTbl $item)
  {
    $sqlF->deleteItem($item->getArray(), "organisme_tbl");
  }
  public static function getAll($sqlF)
  {
    $query = $sqlF->execQuery("SELECT * FROM organisme_tbl");
    $return = [];
    foreach ($query as $element) {
      $entity = new OrganismeTbl();
      $entity->ID_ORGA = $element["ID_ORGA"];
      $entity->NOM_ORGANISATION = $element["NOM_ORGANISATION"];
      $entity->DATE_ORGANISME = $element["DATE_ORGANISME"];
      $entity->INFO_MUTUELLE = $element["INFO_MUTUELLE"];
      $entity->LOGO_ORGANISME = $element["LOGO_ORGANISME"];
      $entity->ALLOWED_ORGA = $element["ALLOWED_ORGA"];
      $return[] = $entity;
    }
    return (count($return) > 1) ? $return : $return[0];
  }
  public static function getOrganismeTblBy($sqlF, $key, $value)
  {
    $query = $sqlF->execQuery("SELECT * FROM organisme_tbl WHERE $key=$value");
    $return = [];
    foreach ($query as $element) {
      $entity = new OrganismeTbl();
      $entity->ID_ORGA = $element["ID_ORGA"];
      $entity->NOM_ORGANISATION = $element["NOM_ORGANISATION"];
      $entity->DATE_ORGANISME = $element["DATE_ORGANISME"];
      $entity->INFO_MUTUELLE = $element["INFO_MUTUELLE"];
      $entity->LOGO_ORGANISME = $element["LOGO_ORGANISME"];
      $entity->ALLOWED_ORGA = $element["ALLOWED_ORGA"];
      $return[] = $entity;
    }
    if (count($return))
      return (count($return) > 1) ? $return : $return[0];
    else
      return false;
  }
}
