<?php
Main::setConnexion($vars,$template);
$a=sessionVar::get(["context"=>"public","name"=>"alert"]);
if(!empty($a)){
    $vars->alert=easyFrameWork::getMicroTtpl("alert");
    $vars->contentAlert=$a;
    sessionVar::unset(["context"=>"public","name"=>"alert"]);
}
require_once "include/param.php";
if (isset($_GET["act"])) {
    if ($_GET["act"] == "deconnexion") {
        session_destroy();
        header("Location:index.php");
    }
} else {
    if( $user=Main::getUSer())
        $vars->user=$user->getArray();
    $template->addScript("window.onload=function(){
        document.querySelectorAll('.card-logo').forEach(el=>{
          // console.dir(el);
            el.addEventListener('click',function(){
                let xhr=new XMLHttpRequest();
                xhr.onreadystatechange = function () {
  //Appelle une fonction au changement d'état.
  if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
     let orga=JSON.parse(xhr.responseText);
    let {tel,adresse}=JSON.parse(orga.INFO_MUTUELLE);
              var nom=orga.NOM_ORGANISATION;
              var _adresse=adresse[0]+\"<br>\"+adresse[1]+\" \"+adresse[2];
              modal(\"<h3>\"+nom+\"</h3><div>\"+_adresse+\"</div>\");
  }
};
                ID=this.getAttribute('data-orga');
                var url=\"{async file=\"async/index.async.php\" fnc=\"getMutuelle\" args=[ID:\"+ID]}
                console.log(url);
              xhr.open(\"GET\",url,true);
              xhr.send();
             
            });\n
        });\n
    }\n");
  //  easyFrameWork::Debug($user->getArray());
    $SQL2V = new SQLtoView($sqlF, "sqlView/index/displayAcademi.view");
    $param = [];
    //   $vars->hashPassWord=easyFrameWork::encrypt(easyFrameWork::hashString("123456"),ENC_KEY);
    $param["query"] = "SELECT IFNULL(u.ID_ORGA,\"NULL\") as IDORGA, ID_SUJET,LIB_SUJET,IFNULL(LOGO_ORGANISME,\"NO_LOGO\") AS LOGO,DATE_SUJET,(SELECT CONTENT_MESSAGE FROM message_tbl m WHERE m.ID_SUJET=s.ID_SUJET ORDER BY m.DATE_MESSAGE LIMIT 1) as LAST_MESSAGE,LIB_THEME,IFNULL(NOM_ORGANISATION,\"GLOBALE\") AS NOM_ORGA FROM `sujet_tbl` s LEFT JOIN utilisateur_tbl u on u.ID_USER=s.ID_USER " .
        "LEFT JOIN theme_tbl t ON t.ID_THEME=s.ID_THEME " .
        "LEFT JOIN organisme_tbl o ON o.ID_ORGA=u.ID_ORGA";
    $template->_view("sujet", $SQL2V, $param);
}
