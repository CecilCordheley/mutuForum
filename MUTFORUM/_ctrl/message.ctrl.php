<?php
//ici le code PHP de la page
#$template pour gérer le moteur
#$vars pour gérer les variables {var:???}

Main::setConnexion($vars,$template);
if (Main::getUSer() && !Main::isBanned($sqlF)) {
    $user=Main::getUSer();
    $vars->user=$user->getArray();
    $SQL2V = new SQLtoView($sqlF, Router::getView() . "/last.view");
    $param = [];
    $last = Request::get("last") ?? null;
    if ($last != null) {
        $vars->titleMessage = "Derniers messages postés";
        $template->callTemplate("last", "message/last.tpl");
        $param["query"] = "SELECT m.ID_SUJET,ID_MESSAGE,DATE_FORMAT(DATE_MESSAGE, \"%D %b %Y\") as DATE_M,CONTENT_MESSAGE,PSEUDO_USER,LIB_SUJET FROM `message_tbl` m INNER JOIN utilisateur_tbl u on u.ID_USER=m.ID_USER INNER JOIN sujet_tbl s ON s.ID_SUJET=m.ID_SUJET WHERE DATE_MESSAGE=(SELECT MAX(DATE_MESSAGE) FROM message_tbl m1 WHERE m1.ID_SUJET=m.ID_SUJET)";
        $template->_view("last_messages", $SQL2V, $param);
    } else {
        $template->callTemplate("last", "message/all.tpl");
        $param["view"] = Router::getView() . "/all.view";
        $param["query"] = "SELECT m.ID_SUJET,ID_MESSAGE,DISPLAY_,DATE_FORMAT(DATE_MESSAGE, \"%D %b %Y\") as DATE_M, (SELECT NOM_ORGANISATION FROM sujet_tbl suj INNER JOIN utilisateur_tbl u ON suj.ID_USER=u.ID_USER INNER JOIN organisme_tbl o ON o.ID_ORGA=u.ID_ORGA WHERE suj.ID_SUJET=m.ID_SUJET) as ORGA,  CONTENT_MESSAGE, PSEUDO_USER,LIB_SUJET FROM `message_tbl` m INNER JOIN utilisateur_tbl u on u.ID_USER=m.ID_USER LEFT JOIN sujet_tbl s ON s.ID_SUJET=m.ID_SUJET ORDER BY m.ID_SUJET";
        $param["callback"] = function (&$item, $previousItem, $defaultString) {
            if($item["DISPLAY_"]==0){
                $item["CONTENT_MESSAGE"]="Message masqué";
            }
            $item["CONTENT_MESSAGE"] = str_replace("[b]", "<b>", $item["CONTENT_MESSAGE"]);
            $item["CONTENT_MESSAGE"] = str_replace("[/b]", "</b>", $item["CONTENT_MESSAGE"]);
            $item["CONTENT_MESSAGE"] = str_replace("[i]", "<i>", $item["CONTENT_MESSAGE"]);
            $item["CONTENT_MESSAGE"] = str_replace("[/i]", "</i>", $item["CONTENT_MESSAGE"]);
            $item["ORGA"] = ($item["ORGA"] != NULL) ? $item["ORGA"] : "GLOBAL";
            if ($previousItem == null || $previousItem["ID_SUJET"] != $item["ID_SUJET"]) {

                return "<div class='headerMessage'><h3>#LIB_SUJET# <span>#ORGA#</span></h3><a href=\"sujet-#ID_SUJET#-#LIB_SUJET#.html\">Voir ce sujet</a></div>$defaultString";
            } else
                return $defaultString;
        };
        $template->_view("allMessage", $SQL2V, $param);
        /*  $template->addScript("window.addEventListener(\"load\", (event) => {
        setCollapse();
      });");*/
    }
}else{
    $template->replaceView("messageByTopic","");
   if(!Main::getUSer())
        $vars->last="<div class=\"alert alert-primary\" role=\"alert\">Vous devez vous connecter pour voir les messages</div>";
}
