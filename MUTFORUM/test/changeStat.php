<?php
//Page pour tester les changement d'état d'un utilisateur
require_once "../_class/_master/easyFrameWork.class.php";
require_once "../_class/_master/autoload.class.php";
easyFrameWork::INIT("../_class/_master","../include/router.json");
Autoloader::register();
Autoloader::callRequires("../_class/_master");
Router::Init("../include/router.json");
//SQlFactory
$sqlF=new SQLFactoryV2(null,"../include/config.ini");
SqlEntities::$DIRECTORY="../SQLEntities";
SqlEntities::LoadEntity("utilisateur_tbl");

//On peut utiliser les utilisateurs
$user=UtilisateurTbl::getUtilisateurTblBy($sqlF,"ID_USER","19");
//$user->changeStat($sqlF,1,"Rédemptiom accordée");
var_dump($user->getLastState($sqlF));