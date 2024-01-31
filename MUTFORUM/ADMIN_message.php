<?php
session_start();

require_once "_class/_master/easyFrameWork.class.php";
/* Initialise le FrameWork et ses dépendances */
easyFrameWork::INIT();
Autoloader::register();
Autoloader::callRequires();
Router::Init();
$template=new easyTemplate();
$vars=new EasyTemplate_variable();
$template->loop("menu",Main::$links);
$sqlF=new SQLFactoryV2(null,"include/config.ini");
//$template->callTemplate("mainContent",Router::getTemplate());
Router::setMainTemplate($template,"mainContent");
include(Router::getCtrl());
Router::LoadStyles($template);
$template->loadDictionnary($vars);
$template->display();