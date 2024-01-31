<?php
session_start();
require_once "_class/_master/easyFrameWork.class.php";
/* Initialise le FrameWork et ses dépendances */
easyFrameWork::INIT();

$sqlF=new SQLFactoryV2();
SQLEntities::generateEntity($sqlF,"reponse_tbl");
/*$user=utilisateur_tbl::Connexion($sqlF,"TEST",easyFrameWork::Hash("123456"));
var_dump($user->MAIL_USER);*/