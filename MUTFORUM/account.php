<?php
session_start();
require_once "_class/_master/autoload.class.php";
Autoloader::register();
Autoloader::callRequires();
Router::Init();
$template=new easyTemplate();
$vars=new EasyTemplate_variable();
$template->loop("menu",Main::$links);
//$template->callTemplate("mainContent",Router::getTemplate());
Router::setMainTemplate($template,"mainContent");
$sqlF=new SQLFactoryV2(null,"include/config.ini");
include(Router::getCtrl());
Router::LoadStyles($template);
$template->loadDictionnary($vars);
$template->display();