<?php
//ici le code PHP de la page
#$template pour gérer le moteur
#$vars pour gérer les variables {var:???}
Main::setConnexion($vars, $template);
$id = Request::get("id");
//var_dump($_POST);
//easyFrameWork::Debug($_GET);
if (count($_POST)) {
    $url = "sujet-$id-" . $_GET["name"] . ".html";
    //  easyFrameWork::Debug($url);
    if (Main::getUSer()) {
        $user = Main::getUSer();
        $vars->user = $user->getArray();
    }
    if (isset($_POST["message"])) {
        SqlEntities::LoadEntity("message_tbl");
        $m = new MessageTbl();
        if (preg_match("#<([a-z A-Z]+)>#", $_POST["message"])) {
            echo "POP";
        } else {
            $message = $_POST["message"];
            $message = htmlentities($message);
            $m->CONTENT_MESSAGE = $message;
            $m->ID_SUJET = $id;
            $m->ID_USER = $user->ID_USER;
            $m->DISPLAY_=1;
            $m->DATE_MESSAGE = date("Y-m-d h:i:s");
            MessageTbl::add($sqlF, $m);
            header("Location:$url");
        }
    } elseif (isset($_POST["reponse"])) {
        //   easyFrameWork::Debug($_GET);
        SqlEntities::LoadEntity("reponse_tbl");
        $r = new ReponseTbl();
        $r->ID_MESSAGE = $_POST["MessageReponse"];
        $r->ID_USER = $user->ID_USER;
        $r->CONTENT_REPONSE = htmlentities($_POST["reponse"]);
        $r->DATE_REPONSE = date("Y-m-d h:i:s");
        $r->DISPLAY=1;
        ReponseTbl::add($sqlF, $r);
        header("Location:$url");
    }
}

if (isset($id)) {
    $a = $sqlF->get("sujet_tbl", "ID_SUJET=$id");
    if ($user = Main::getUSer()) {
        $vars->user = $user->getArray();
        switch ($user->getLastState($sqlF)["ID"]) {
            case 1: {
                    $form = easyFrameWork::getMicroTtpl("postMessage");
                    $vars->postMessage = $form;
                    $vars->SUJET = $a[0];
                    $SQL2V = new SQLtoView($sqlF, Router::getView() . "/messageBySujet.view");
                    $param = [];
                    $param["query"] = "SELECT (SELECT COUNT(*) FROM reponse_tbl r WHERE r.ID_MESSAGE=m.ID_MESSAGE) as NB_REPONSE, DISPLAY_,ID_MESSAGE,AVATAR_USER, CONTENT_MESSAGE,m.ID_USER,PSEUDO_USER,DATE_FORMAT(DATE_MESSAGE,\"%d/%m/%Y\") AS _DATE FROM message_tbl m INNER JOIN utilisateur_tbl u ON  u.ID_USER=m.ID_USER WHERE ID_SUJET=$id";
                    $param["callback"] = function (&$item, $previousItem, $defaultString) {
                        if ($item["DISPLAY_"] == 0) {
                            $item["CONTENT_MESSAGE"] = "<em>Message masqué par la modération</em>";
                        } else {
                            $item["CONTENT_MESSAGE"] = str_replace("[b]", "<b>", $item["CONTENT_MESSAGE"]);
                            $item["CONTENT_MESSAGE"] = str_replace("[/b]", "</b>", $item["CONTENT_MESSAGE"]);
                            $item["CONTENT_MESSAGE"] = str_replace("[i]", "<i>", $item["CONTENT_MESSAGE"]);
                            $item["CONTENT_MESSAGE"] = str_replace("[/i]", "</i>", $item["CONTENT_MESSAGE"]);
                            $item["CONTENT_MESSAGE"] = str_replace("\n", "<br>", $item["CONTENT_MESSAGE"]);
                        }
                        return $defaultString;
                    };
                    $template->_view("messageByTopic", $SQL2V, $param);
                    SqlEntities::LoadEntity("message_tbl");
                    $messages = MessageTbl::getMessageTblBy($sqlF, "ID_SUJET", $id);
                    foreach ($messages as $m) {
                        $reponse = $m->getReponses($sqlF);
                        //    var_dump($reponse);
                        $i = 0;

                        $return = (gettype($reponse) == "array") ? $reponse : [$reponse];
                       // var_dump($return);
                        $r = SqlEntities::getArrayEntities($return, function ($carry, $item) use (&$i, $sqlF) {

                            $carry[$i] = $item->getArray();
                            if(!$item->DISPLAY)
                            $carry[$i]["CONTENT_REPONSE"]="Message supprimer par la modération";
                            preg_match("/([0-9]{4}-[0-9]{2}-[0-9]{2})/i", $item->DATE_REPONSE, $matches);
                            $carry[$i]["SHORT_DATE"] = $matches[1];
                            $carry[$i]["LOGIN"] = UtilisateurTbl::getUtilisateurTblBy($sqlF, "ID_USER", $item->ID_USER)->PSEUDO_USER;

                            $i++;

                            return $carry;
                        });

                        if (count($r)) {
                            //  var_dump($r);
                            $template->loop("reponse_" . $m->ID_MESSAGE, $r);
                        }
                    }
                    $template->addScript("window.addEventListener('load', (event) => {
                        document.querySelectorAll('[form-target]').forEach((el)=>{el.style.display='none'})
                        document.querySelectorAll('[data-target]').forEach((el)=>{
                            el.onclick=function(){
                                let _target=el.getAttribute('data-target');
                                document.querySelector('[form-target='+_target+']').style.display='block';
                                return false;
                            }
                        });
                        var message=document.querySelector('#messageInput');
                        var length=document.getElementById('lengthMessage');
                        message.addEventListener('focus',()=>{
                            message.classList.remove('unvalide');
                        });
                        message.addEventListener('keyup',function(){
                            length.innerHTML=message.value.length+\"/255\";
                        })});");
                    break;
                }
            case 2: {
                    $vars->connexion = "Vous ne pouvez pas poster de messages !";
                    $SQL2V = new SQLtoView($sqlF, Router::getView() . "/messageBySujet.view");
                    $param = [];
                    $param["query"] = "SELECT ID_MESSAGE, CONTENT_MESSAGE,m.ID_USER,PSEUDO_USER,DATE_FORMAT(DATE_MESSAGE,\"%d/%m/%Y\") AS _DATE FROM message_tbl m INNER JOIN utilisateur_tbl u ON  u.ID_USER=m.ID_USER WHERE ID_SUJET=$id";
                    $param["callback"] = function (&$item, $previousItem, $defaultString) {
                        $item["CONTENT_MESSAGE"] = str_replace("[b]", "<b>", $item["CONTENT_MESSAGE"]);
                        $item["CONTENT_MESSAGE"] = str_replace("[/b]", "</b>", $item["CONTENT_MESSAGE"]);
                        $item["CONTENT_MESSAGE"] = str_replace("[i]", "<i>", $item["CONTENT_MESSAGE"]);
                        $item["CONTENT_MESSAGE"] = str_replace("[/i]", "</i>", $item["CONTENT_MESSAGE"]);
                        $item["CONTENT_MESSAGE"] = str_replace("\n", "<br>", $item["CONTENT_MESSAGE"]);
                        return $defaultString;
                    };
                    break;
                }
            case 3: {
                    $vars->connexion = "Vous avez été bannis, vous ne pouvez accéder au messages postés!";
                    $re = '/([0-9]{4}\-[0-9]{2}\-[0-9]{2}) ([0-9]{2}\:[0-9]{2}\:[0-9]{2})/m';
                    $str = $user->getLastState($sqlF)["DATE"];
                    $subst = "le $1 à $2";
                    $date = preg_replace($re, $subst, $str);
                    $template->replaceView("messageByTopic", "<div class=\"alert alert-danger\" role=\"alert\">" .
                        "Vous avez été banni le " . $date .
                        " pour motif : <strong>" . $user->getLastState($sqlF)["MOTIF"] . "</strong></div>");
                    $template->clearView("messageByTopic");
                    break;
                }
        }
    } else {
        if (!Main::getUSer())
            $vars->ResultMessage = "<div class=\"alert alert-primary\" role=\"alert\">Vous devez vous connecter pour voir les messages</div>";
        $template->clearView("messageByTopic");
    }
} else {
    $vars->user = $user->getArray();
    $template->clearView("messageByTopic");
    //$vars->ResultMessage="Séléctionnez un sujet";
    $vars->SUJET = ["ID_SUJET" => 0, "LIB_SUJET" => "Séléctionnez un sujet"];
    if (!Main::getUSer())
        $vars->last = "<div class=\"alert alert-primary\" role=\"alert\">Vous devez vous connecter pour voir les messages</div>";
}
