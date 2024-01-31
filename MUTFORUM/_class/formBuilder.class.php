<?php
/**
 * Permet de générer des composant formulaire <form></form>
 */
class FormBuilder
{
    /**
     * @var $compoment ensemble des composant du formulaire
     */
    private array $compoment;
    private string $action;
    private string $method;
    private string $pattern;
    public function __construct($action, $method, $pattern = "")
    {
        $this->compoment = [];
        $this->action = $action;
        $this->method = $method;
        $this->pattern = $pattern ?? null;
    }
    public function addCompoment($param)
    {
      
        $this->compoment[$param["ID"]]=[
            "multiple"=>$param["multiple"]??false,
            "label" => $param["label"] ?? $param["ID"],
            "value" => $param["value"] ?? "\"\""
        ];
        foreach($param as $key=>$value){
            $this->compoment[$param["ID"]][$key]=$value;
        }
        return $this;
    }
    public function generate($param=null)
    {
        $return = "<form action='[#action#]' method='[#methode#]' enctype=\"multipart/form-data\">[...]</form>";
        $return = str_replace("[#action#]", $this->action, $return);
        $return = str_replace("[#methode#]", $this->method, $return);
        $p = $this->pattern ?? "<label for=\"[#ID#]\">[#label#]</label><input  id=\"[#ID#]\" type=\"[#type#]\" name=\"[#name#]\" value='[#value#]' [#required#]>";
        array_walk($this->compoment, function ($item, $key) use (&$return, $p) {
            
            $compoment = $p . "\n\t";
            if(isset($item["className"])){
              $compoment= str_replace("<input ","<input class=\"".$item["className"]."\"",$compoment);
            }
            if ($item["type"] == "select") {
              //  easyFrameWork::Debug($compoment);
                //check if class exist
                $re="/\<input (class=\".*?\").*?\>/m";
                preg_match($re,$compoment,$matches);
                $class=$matches[1]??"";
                $re = '/\<input.*?\>/m';
                //   $compoment = '<input type=[#type#]>';
                $subst = "<select $class name=[#name#] id=[#ID#] [#multiple#] [#required#]>[#data#]</select>";
                $compoment = preg_replace($re, $subst, $compoment);
                
                    $m=($item["multiple"])?"multiple=".$item["multiple"]:"";
                $compoment = str_replace("[#multiple#]", $m, $compoment);
                foreach ($item['data'] as $data) {
                    $compoment = str_replace("[#data#]", "<option value=\"" . $data["val"] . "\">" . $data["label"] . "</option>[#data#]", $compoment);
                }
                $compoment = str_replace("[#data#]", "", $compoment);
            } else {
             //   var_dump($item["value"]);
          //   easyFrameWork::Debug($compoment);
                $compoment = str_replace("[#value#]", $item["value"], $compoment);
                
                $compoment = str_replace("[#type#]", $item["type"], $compoment);
            }
            $required=(isset($item["required"]))?"required='".$item["required"]."'":"";
       //    easyFrameWork::Debug($compoment);
            $compoment = str_replace("[#required#]", $required, $compoment);
            $compoment = str_replace("[#label#]", $item["label"], $compoment);
            $compoment = str_replace("[#name#]", $item["name"], $compoment);
            if(isset($item["disabled"]))
                $compoment = str_replace("[#disable#]", $item["disabled"], $compoment);
            else
            $compoment = str_replace("[#disable#]", "", $compoment);
            $compoment = str_replace("[#ID#]", $key, $compoment);
            $return = str_replace("[...]", " $compoment [...]\n", $return);

        });
        if($param!=null){
            $return = str_replace("[...]", $param["submit"]."[...]\n", $return);
        }
        $return = str_replace("[...]", "", $return);
        return $return;
    }
}