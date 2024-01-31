<?php
class Autoloader
{

    /**
     * Enregistre notre autoloader
     */
    static function register()
    {
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }

    /**
     * Inclue le fichier correspondant à notre classe
     * @param $class string Le nom de la classe à charger
     */
    static function autoload($class)
    {
        require '_class/' . $class . '.class.php';
    }
    static function callRequires($uri="_class/_master")
    {
        $files = scandir($uri);
        //var_dump($files);
        array_walk($files,function($item,$key){
            if($item!="autoload.class.php" && $item!="." && $item!="..")
                require_once $item;
        });
    }
}
