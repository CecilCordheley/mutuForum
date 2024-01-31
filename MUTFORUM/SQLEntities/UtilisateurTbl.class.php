<?php
class UtilisateurTbl
{
  private $attr = ["ID_USER" => '', "AVATAR_USER" => "", "DATA_USER" => "", "VALID_USER" => "", "PSEUDO_USER" => '', "MAIL_USER" => '', "MDP_USER" => '', "ARRIVE_UTILISATEUR" => '', "ID_ORGA" => '', "TYPE_UTILISATEUR" => ''];
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
  public function getTypeUSer($sqlf)
  {
    SqlEntities::LoadEntity("type_utilisateur");
    return TypeUtilisateur::getTypeUtilisateurBy($sqlf, "idType_Utilisateur", $this->TYPE_UTILISATEUR);
  }
  public function changeStat($sqlF, $state, $motif = "")
  {
    $a = [];
    $a["USER"] = $this->ID_USER;
    $a["STATE"] = $state;
    $a["DATE_STATE"] = date("Y-m-d H:i:s");
    $a["MOTIF_STATE"] = $motif;
    $sqlF->addItem($a, "utilisateur_tbl_has_stateuser_tbl");
  }
  public static function Connexion($sqlF, $mail, $mdp)
  {
 //   echo "SELECT * FROM utilisateur_tbl WHERE MAIL_USER='$mail' AND MDP_USER='$mdp'";
    $query = $sqlF->execQuery("SELECT * FROM utilisateur_tbl WHERE MAIL_USER='$mail' AND MDP_USER='$mdp'");
    $return = [];
    if (!empty($query)) {
      foreach ($query as $element) {
        $entity = new UtilisateurTbl();
        $entity->ID_USER = $element["ID_USER"];
        $entity->PSEUDO_USER = $element["PSEUDO_USER"];
        $entity->MAIL_USER = $element["MAIL_USER"];
        $entity->AVATAR_USER = $element["AVATAR_USER"]??"";
        $entity->DATA_USER = $element["DATA_USER"]??"";
        $entity->MDP_USER = $element["MDP_USER"];
        $entity->ARRIVE_UTILISATEUR = $element["ARRIVE_UTILISATEUR"];
        $entity->ID_ORGA = $element["ID_ORGA"];
        $entity->TYPE_UTILISATEUR = $element["TYPE_UTILISATEUR"];
        $entity->VALID_USER = $element["VALID_USER"]??0;
        $return[] = $entity;
      }
    }
    return (count($return)) ? $return[0] : false;
  }

  public static function  add(SQLFactoryV2 $sqlF, UtilisateurTbl $item)
  {
    $sqlF->addItem($item->getArray(), "utilisateur_tbl");
  }
  public static function  update(SQLFactoryV2 $sqlF, UtilisateurTbl &$item)
  {
    $item->DATA_USER = str_replace("\"", "\\\"", ($item->DATA_USER));
    $sqlF->updateItem($item->getArray(), "utilisateur_tbl");
  }
  public static function  del(SQLFactoryV2 $sqlF, UtilisateurTbl $item)
  {
    $sqlF->deleteItem($item->getArray(), "utilisateur_tbl");
  }
  /**
   * @return OrganismeTbl
   */
  public function getOrganisation($sqlf)
  {
    SqlEntities::LoadEntity("organisme_tbl");
    return OrganismeTbl::getOrganismeTblBy($sqlf, "ID_ORGA", $this->ID_ORGA);
  }
  public static function getAll($sqlF)
  {
    $query = $sqlF->execQuery("SELECT * FROM utilisateur_tbl");
    $return = [];
    foreach ($query as $element) {
      $entity = new UtilisateurTbl();
      $entity->ID_USER = $element["ID_USER"];
      $entity->PSEUDO_USER = $element["PSEUDO_USER"];
      $entity->MAIL_USER = $element["MAIL_USER"];
      $entity->AVATAR_USER = $element["AVATAR_USER"];
      $entity->DATA_USER = $element["DATA_USER"];
      $entity->MDP_USER = $element["MDP_USER"];
      $entity->ARRIVE_UTILISATEUR = $element["ARRIVE_UTILISATEUR"];
      $entity->ID_ORGA = $element["ID_ORGA"];
      $entity->TYPE_UTILISATEUR = $element["TYPE_UTILISATEUR"];
      $entity->VALID_USER = $element["VALID_USER"];
      $return[] = $entity;
    }
    return (count($return) > 1) ? $return : $return[0];
  }
  /**
   * @param SqlFactoryV2 $sqlf
   */
  public function getMessages($sqlf)
  {
    SqlEntities::LoadEntity("message_tbl");
    return messageTbl::getMessageTblBy($sqlf, "ID_USER", $this->ID_USER);
  }
  public function getUserData()
  {
    return json_decode($this->DATA_USER);
  }
  public static function getUtilisateurTblBy($sqlF, $key, $value)
  {
    $query = $sqlF->execQuery("SELECT * FROM utilisateur_tbl WHERE $key=$value");
  //  echo "SELECT * FROM utilisateur_tbl WHERE $key=$value";
    $return = [];
    foreach ($query as $element) {
      $entity = new UtilisateurTbl();
      $entity->ID_USER = $element["ID_USER"];
      $entity->PSEUDO_USER = $element["PSEUDO_USER"];
      $entity->MAIL_USER = $element["MAIL_USER"];
      $entity->AVATAR_USER = $element["AVATAR_USER"];
      $entity->DATA_USER = $element["DATA_USER"];
      $entity->MDP_USER = $element["MDP_USER"];
      $entity->ARRIVE_UTILISATEUR = $element["ARRIVE_UTILISATEUR"];
      $entity->ID_ORGA = $element["ID_ORGA"];
      $entity->TYPE_UTILISATEUR = $element["TYPE_UTILISATEUR"];
      $entity->VALID_USER = $element["VALID_USER"];
      $return[] = $entity;
    }
    return (count($return) > 1) ? $return : $return[0]??[];
  }
  /**
   * @param SQLFactoryV2 $sqlF
   */
  public function getStates($sqlF)
  {
    $state = $sqlF->execQuery("SELECT * FROM utilisateur_tbl_has_stateuser_tbl us INNER JOIN stateuser_tbl s ON s.ID_STATE=us.STATE WHERE USER=" . $this->ID_USER . " ORDER BY DATE_STATE");
    $i = 0;
    return array_reduce($state, function ($carry, $item) use (&$i) {
      $carry[$i]["ID"] = $item["ID_STATE"];
      $carry[$i]["DATE"] = $item["DATE_STATE"];
      $carry[$i]["MOTIF"] = $item["MOTIF_STATE"] ?? "";
      $carry[$i]["LIBELLE"] = $item["LIBELLE_STATE"];
      $i++;
      return $carry;
    }, []);
  }
  public function getLastState($sqlF)
  {
    $return = $this->getStates($sqlF);
    return $return[count($return) - 1];
  }
}
