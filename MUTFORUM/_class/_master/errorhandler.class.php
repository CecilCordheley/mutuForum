<?php
require 'iObserver.php';
/**
 * ErrorHandler vous offre la capacité de gérer les erreurs de votre systeme. 
 *
 * @author Cecil Cordheley
 */
class ErrorHandler implements ISubject
{

    private $_observers;
    private $current;
    private $errorCat;
    private $triggered;
    public function __construct()
    {
        $this->triggered=false;
        $this->_observers = array();
        $this->current = new stdClass();
        $this->errorCat=["E_USER_ERROR","E_USER_WARNING","E_USER_NOTICE"];
    }
    public function isTriggered(){
        return  $this->triggered;
    }

    /**
     * Fonction qui permet de gérer l'erreur
     * @param string $errstr Message de l'erreur
     * @param string $errfile nom du fichier dans lequel l'erreur s'est produite.
     * @param string $errline ligne concernée. 
     */
    public function error($errno, $errstr, $errfile, $errline)
    {
        $this->triggered=true;
      //  var_dump($this->triggered);
    //  echo $errno;
        $this->current->errorString = $errstr;
        $this->current->errorFile = $errfile;
        $this->current->errorLine = $errline;
        $this->current->errorNum = $errno;
        $this->current->errorNo=$this->errorCat[$errno];
        if($errno==2)
            $this->notifyObs();
        else
            echo "Ce n'est une E_NOTICE";
       
        
        exit(1);
    }
    /**
     * retourne l'erreur 
     * @return stdClass
     */
    public function getError()
    {
        return $this->current;
    }
    public static function getFatalError(){
        $filePath = "./include/error.txt";
        if (file_exists($filePath)) {
            // Lire le contenu du fichier
            $content = file_get_contents($filePath);
        } else {
            $content = "No Error occurences";
        }
        return $content;
    }
    /**
     * Reporte une erreur autre E_NOTICE
     */
    public static function errorFatal()
    {
        error_reporting(0);
        $error = error_get_last();
        // var_dump($error);
        if ($error) {
            $current = new StdClass();
            $current->errorString = $error["message"];
            $current->errorFile = $error["file"];
            $current->errorLine = $error["line"];
            $current->errorNo = "FATAL ERROR";
            // $current->errorNum = $error["line"];

            $filePath = "./include/error.txt";

            // Vérifier si le fichier existe
            if (file_exists($filePath)) {
                // Lire le contenu du fichier
                $content = file_get_contents($filePath);
                $content .= "\n-----------------------";
            } else {
                echo "Le fichier n'existe pas. Création...\n";
                $content = "";
            }

            // Ajouter les nouvelles informations au contenu
            $content .= "Erreur : [$current->errorNo] $current->errorString - ";
            $content .= "Dans le fichier : $current->errorFile - ";
            $content .= "À la ligne : $current->errorLine - ";
            $msg=explode(" in ",$current->errorString)[0];
            // Écrire le nouveau contenu dans le fichier
            file_put_contents($filePath, $content);
           // $fatalErrorContent=file_get_contents($filePath);
         //   echo $fatalErrorContent;
           header("Location:error_trigger.php?msg='$msg'&pageName=$current->errorFile&line=$current->errorLine");
        }
    }

    /**
     * permet d'attacher un Observeur d'erreur 
     * @param Iobserver $obs observateur
     */
    public function attach($obs)
    {
        $this->_observers[] = $obs;
    }
/**
 * Permet de retirer un obsever
 * @param IObserver $obs observateur a retiré
 */
    public function detach($obs)
    {
        if (is_int($key = array_search($obs, $this->_observers, true))) {
            unset($this->_observers[$key]);
        }
    }
/**
 * Déclenche la fonction 'update' des observers associés
 */
    public function notifyObs()
    {
        foreach ($this->_observers as $observer) {
            try {
                $observer->update($this); // délégation
            } catch (\Exception $e) {
                die($e->getMessage());
            }
        }
    }

}
/**
 * Fourni le composant pour ajouter dans un fichier l'erreur E_NOTICE 
 */
class LogFile implements IObserver
{

    /**
     * chemin d'accès du fichier
     * @var string
     */
    private $file;

    /**
     * créer une instance de LogFile
     * @param string $filepath chemin d'accès du fichier
     */
    public function __construct($filepath)
    {
        $this->file = $filepath;
    }

    public function update($object)
    {
        if(get_class($object)=="ErrorHandler")
            $this->writeError($object->getError());
        else    
             $this->writeError($object);
    }

    /**
     * Ecrit l'erreur dans le fichier
     * @param stdClass $error object erreur (message,fichier,ligne)
     */
    private function writeError($error)
    {
      //  echo $this->file;
        if (file_exists($this->file)) {
            $content = file_get_contents($this->file);
            $content .= "\n";
        } else
            $content = "";
           
        $content .= date("Y/m/d : H:i:s") . " - No [".$error->errorNo."] - Erreur : " . $error->errorString . " - Fichier : " . $error->errorFile . " - Ligne :" . $error->errorLine;
       // echo "Debug File / $content";
        echo file_put_contents($this->file, $content);
    }

}
/**
 * Affiche l'erreur dans la page
 */
class Notifier implements IObserver
{

    private $pattern;
    public function __construct($pattern = "")
    {
        if ($pattern != "")
            $this->pattern = $pattern;
        else {
            $msg = "";
            $msg .= "<style>*{font-family:Arial;}.error_tbl{width:100%;background:#FF9;}\n.error_tbl tr:first-of-type{background:#F00;color: #FFF;
            font-weight: bold;}</style>";
            $msg .= "Une erreur est survenue<br/>";

            $msg .= "<table class='error_tbl'><tr><td>Message : </td><td>[#errorString#]</td></tr>" .
                "<tr><td>Fichier : </td><td>[#errorFile#]</td></tr>" .
                "<tr><td>Ligne : </td><td>[#errorLine#]</td></tr></table>";
            $this->pattern = $msg;
        }
    }
    public function update($object)
    {
        $e = $object->getError();
        $this->ShowError($e);
    }

    private function ShowError($error)
    {
        $msg = $this->pattern;
        $msg = str_replace("[#errorString#]", $error->errorString, $msg);
        $msg = str_replace("[#errorFile#]", $error->errorFile, $msg);
        $msg = str_replace("[#errorLine#]", $error->errorLine, $msg);
        $msg = str_replace("[#errorType#]", $error->errorNum, $msg);
        echo $msg;
    }

}

class Message implements IObserver
{

    /**
     * tableau stockant les messages d'erreur
     * @var array
     */
    private $_messages;

    public function __construct()
    {
        $this->_messages = array();
    }

    public function update($object)
    {
        $this->_messages[] = $object->getError();
    }

    public function showError()
    {
        $msg = "";
        foreach ($this->_messages as $error) {
            $msg .= "<style>.error_tbl{width;100% background:#F00;}</style>Une erreur est survenue<br/>";
            $msg .= "<table class='error_tbl'><tr><td>Message : </td><td>" . $error->errorString . "</td></tr>" .
                "<tr><td>Fichier : </td><td>" . $error->errorFile . "</td></tr>" .
                "<tr><td>Ligne : </td><td>" . $error->errorLine . "</td></tr></table>";
        }
        echo $msg;
    }

}

class SysLog implements IObserver
{

    /**
     * 
     * @param ErrorHandler $object
     */
    public function update($object)
    {

        $e = $object->getError();
        openlog("ErrorLog", LOG_PID | LOG_PERROR, 'LOG_LOCAL0');
        $access = date("Y/m/d H:i:s");
        syslog(LOG_WARNING, "Error Provider: $access {$_SERVER['REMOTE_ADDR']} ({$_SERVER['HTTP_USER_AGENT']})" .
            "\nError File:" . $e->errorFile .
            "\nError Line:" . $e->errorLine .
            "\nMessage :" . $e->errorString);
        closelog();
    }

}

?>