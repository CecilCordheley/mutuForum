<?php
/*Utiliser ce fichier via un terminal PHP */
require_once "../_class/_master/easyFrameWork.class.php";
/* Initialise le FrameWork et ses dépendances */
easyFrameWork::INIT("../_class/_master", "../include/router.json");
echo "-- Création de l'URL de la page ainsi que sa redirection\n";
$titre=readline("Indiquez le titre de la redirection : ");
$name = readline("Expression Régulière ou Valeur de l'URL (exemple addproduit.html | produit/[0-9].html: ");
$url="^$name$";
$page=readline("Page de redirection (exemple action.php?act=addproduit | produit.php?id=$1) : ");
$content=file_get_contents("../.htaccess");
$content.="\n#--------------------------------------------------\n#     $titre\n#----------------------\nRewriteRule $url /$page";
file_put_contents("../.htaccess",$content);