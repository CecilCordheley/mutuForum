<?php
abstract class SqlToElement
{
    /**
     * @var SQLFactoryV2
*/
    private $SQLF;
    /**
     * @param $sqlfactory SQLFactoryV2
     */
   
    public function __construct($sqlfactory)
    {
        $this->SQLF = $sqlfactory;

    }

    public function getFactory():SQLFactoryV2{return $this->SQLF;}
    /**
     * @param $param array
     */
    public function generate($param): string
    {
        return "";
    }
}
class SqlToForm extends SqlToElement{
public function generate($param): string
    {
        $url = $param["URI"];
        $method = $param["METHOD"];
        if (key_exists("table", $param)) {
            
            $result = parent::getFactory()->getColumns($param["table"]);
        }
        //var_dump($result);
        $return = array_reduce($result, function ($carry, $item) use ($param) {
            // var_dump($item);
            if (!in_array($item["NAME"], $param["ignoreFields"])) {
                $type = "";
                switch ($item["TYPE"]) {
                    case "varchar": {
                            $type = "text";
                            break;
                        }
                    case "date": {
                            $type = "date";
                            break;
                        }
                    default: {
                            $type = "text";
                            break;
                        }
                }
                $label = ($param["label"]) ? "<label for=\"" . strtolower($item["NAME"]) . "\">" . strtolower($item["NAME"]) . "</label>" : "";
                if (isset($item["TABLE_ASSOC"])) {
                    $assocField = $param["ASSOC_FIELDS"][$item["NAME"]];
                    $fields =  parent::getFactory()->execQuery("SELECT " . $item["NAME"] . ", $assocField FROM " . $item["TABLE_ASSOC"]);
                    $select = array_reduce($fields, function ($carry, $_item) use ($item, $assocField) {
                        $carry .= "<option value=\"" . $_item[$item["NAME"]] . "\">" . $_item[$assocField] . "</option>";
                        return $carry;
                    });
                    $carry .= "<div class=\"form_control\">
        $label <select id=\"" . strtolower($item["NAME"]) . "\"name=\"" . $item["NAME"] . "\"placeholder=\"Séléctionner une valeur\"> $select</select>
            </div>";
                } else {
                    $carry .= "<div class=\"form_control\">   $label
                       <input id=\"" . strtolower($item["NAME"]) . "\" type=\"$type\" name=\"" . $item["NAME"] . "\">
                </div>";
                }
            }
            return $carry;
        }, "");
        return "<form action=\"$url\" method=\"$method\">$return<button type=\"\">Envoyer</button></form>";
    }
}
class SQLtoView extends SqlToElement{
    /**
     * @var string
     */
    private $view;
    public function __construct($sqlfactory,$view=""){
        parent::__construct($sqlfactory);
        if($view!="")
            $this->view=file_get_contents($view);
    }
    private function setView($url){
        if(file_exists($url)){  
            $this->view=file_get_contents($url);
        }else
            throw new Exception("$url doesn't exist");
        return $this;
    }
    public function generate($param):string{
        $i=0;
        $factory=parent::getFactory();
        $result=$factory->execQuery($param["query"]);
        if(key_exists("view",$param))
            $this->setView($param["view"]);
        if($this->view==null){
            throw new Exception("No view parameter");
        }else
        $return = array_reduce($result, function ($carry, $item) use($param,&$i) {
            if(key_exists("callback",$param)){
                $carry["str"].=call_user_func_array($param["callback"],array(&$item,$carry["previous"],$this->view));
            }else{
                $carry["str"].=$this->view;
            }
            foreach($item as $key=>$value){
                $carry["str"]=str_replace("#$key#",$value,$carry["str"]);
            }
            $carry["previous"]=$item;
           // echo $i.$carry["str"];
            $i++;
            
            return $carry;
        },[
            "str"=>"",
            "previous"=>null
        ]);
        if(key_exists("container",$param)){
            $return["str"]=str_replace("[...]",$return["str"],$param["container"]);
        }
        return $return["str"];
    }
}