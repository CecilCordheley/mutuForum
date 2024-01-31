<?php

/**
 * Contient l'ensemble des éléments et fonction utiliser sur le projet
 */
abstract class Main
{
    /**
     * représente l'ensemble des liens
     */
    public static $links = [
        [
            "href" => "message.php",
            "PageName" => "Tout les messages"
        ], [
            "href" => "message/last.html",
            "PageName" => "derniers messages"
        ], [
            "href" => "topic.php",
            "PageName" => "Topic",
            "title" => "tout les sujets"
        ], [
            "href" => "chat.php",
            "PageName" => "Accès au chat"
        ]
    ];
    public static $admin = [
        "href" => "ADMIN_message.php"
    ];
    /**
     * Paramètre l'interface de connexion
     * @param EasyTemplate_variable $vars collection de variables du template
     */
    public static function setConnexion(&$vars, &$template)
    {
        $sqlF = new SQLFactoryV2();
      //  easyFrameWork::Debug(sessionVar::getPrivate("USER"));
        if (sessionVar::getPrivate("USER") != null) {
            SqlEntities::LoadEntity("utilisateur_tbl");

            $user = unserialize(sessionVar::getPrivate("USER"));
            if (self::isBanned($sqlF)) {
                $re = '/([0-9]{4})\-([0-9]{2})\-([0-9]{2}) ([0-9]{2}\:[0-9]{2}\:[0-9]{2})/m';
                $str = $user->getLastState($sqlF)["DATE"];
                $subst = "le $3/$2/$1 à $4";
                $date = preg_replace($re, $subst, $str);
                $vars->alert = "<div class=\"alert alert-danger\" role=\"alert\">" .
                    "Vous avez été banni le " . $date .
                    " pour motif : <strong>" . $user->getLastState($sqlF)["MOTIF"] . "</strong> de ce fait vous ne pouvez plus poster de messages</div>";
            }
            $vars->PSEUDO_USER = $user->PSEUDO_USER;
            if ($user->TYPE_UTILISATEUR != "1") {
                $vars->admin = easyFrameWork::getMicroTtpl("adminMenu");
                // $template->LOOP("adminLoop",self::$admin);
            }
                $vars->connexion = easyFrameWork::getMicroTtpl("userInterface");
        } else {
            $vars->connexion = easyFrameWork::getMicroTtpl("connexion");
        }
    }
    public static function UploadFile($pseudo)
    {
        $target_dir = "img/AVATAR/";
        $filename=easyFrameWork::hashString($pseudo);
        $target_file = $target_dir . $filename;
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
            $check = getimagesize($_FILES["AVATAR_USER"]["tmp_name"]);
            if ($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                $error= "File is not an image.";
                $uploadOk = 0;
            }
        // Check file size
        if ($_FILES["AVATAR_USER"]["size"] > 500000) {
            $error= "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if (
            $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif"
        ) {
            $error= "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            $error= "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["AVATAR_USER"]["tmp_name"], $target_file)) {
                $error= "The file " . htmlspecialchars(basename($_FILES["AVATAR_USER"]["name"])) . " has been uploaded.";
            } else {
                $error= "Sorry, there was an error uploading your file.";
            }
        }
        return ["result"=>$uploadOk,"Filename"=>$filename,"error"=>$error];
    }
    public static function getSelectOrga(SQLFactoryV2 $sqlF)
    {
        $return = [
            [
                "val" => "NULL",
                "label" => "selectionnez une mutuelle"
            ]
        ];
        $data = $sqlF->getTable("organisme_tbl");
        return array_reduce($data, function ($carry, $item) {
            $carry[] = [
                "val" => $item["ID_ORGA"],
                "label" => $item["NOM_ORGANISATION"]
            ];
            return $carry;
        }, $return);
    }
    public static function getSelectTypeUser(SQLFactoryV2 $sqlF)
    {
        $return = [
            [
                "val" => "NULL",
                "label" => "selectionnez un type"
            ]
        ];
        $data = $sqlF->getTable("type_utilisateur");
        return array_reduce($data, function ($carry, $item) {
            $carry[] = [
                "val" => $item["idType_Utilisateur"],
                "label" => $item["LIBELLE_Type_Utilisateur"]
            ];
            return $carry;
        }, $return);
    }
    /**
     * @return mixed
     */
    public static function getUSer()
    {
        if (sessionVar::getPrivate("USER") != null) {
            SqlEntities::LoadEntity("utilisateur_tbl");
            return unserialize(sessionVar::getPrivate("USER"));
        } else
            return false;
    }
    public static function isBanned($sqlF)
    {
        $user = self::getUSer();
        return $user->getLastState($sqlF)['ID'] == 3;
    }
}
