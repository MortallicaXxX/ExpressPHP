<?php
// include __DIR__."\document.xml";

/*
* @{name} thorus
* @{desc} thorus est le noeud propre à chaque éléments contenant
    * Les fonctions de bases
    * Listeners
    * fonction prototypes
*/
class thorus{
  // liste des fonction "type" à appliquer à TOUT DOMelment
  function __construct($domElement , $element_definition = null , $ui = null){

    $th = array(
        "@initialise" => "KINGASV",
        "title" => "CTO"
    );

    $personJSON=json_encode($person);//returns JSON string

    //Json Decode

    $domElement -> __proto__ -> th = "lol";
  }

  function __destruct(){}
}

/*
*
*/
class UIelement {

  public $ClassName;
  public $ui;

  function __construct($e , $root = null , $parentNode = null) {
    $this -> ClassName = 'UIelement';
    if($root)$this -> {$root} = $root;
    if($parentNode)$this -> {$parentNode} = $parentNode;
    $this -> normalise($e);
  }

  function __destruct() {
      // print "Destroying " . __CLASS__ . "\n";
  }

  private function normalise($definition = array()){
    try{
      if(!is_array($definition)){
        if(property_exists($definition , "ClassName"))$definition = (array)$definition;
        else throw new Exception("definition n'est pas un tableau ni une class UIelement");
      }
      foreach ($definition as $key => $value) {
        $this -> {$key} = $value;
      }
      if(property_exists($this , "childrens"))$this -> childrens = new UI($this -> childrens);
    }catch(exception $e){
      echo 'exception: ',  $e->getMessage(), "\n";
    }
  }

}

/*
*
*/
class UI {
  public $templates = array();
  public $ui = array();
  public $root;
  public $parentNode;
  public $ClassName;

  public function __construct($value , $root = null , $parentNode = null) {
    $this -> ClassName = 'UI';
    if($root != null)$this -> root = $root;
    if($parentNode)$this -> parentNode = $parentNode;
    else $this -> parentNode = $this;
    $this -> ui = $this -> normalize($value , $root , $parentNode);
    $this -> templates = $this -> ui;
  }

  function __destruct() {
      // print "Destroying " . __CLASS__ . "\n";
  }

  public function renderIn($doc , $parent, $template = null){
    // Crée un new DOMDocument
    // print_r($parent);
    $toGenerate;
    if(!$template) $toGenerate = $this -> ui;
    else $toGenerate = $this -> templates;
    // print_r($toGenerate);
    foreach ($toGenerate as $key => $e) {
      $child = $doc->createElement($e -> type);

      if(property_exists($e,'prop')){
        foreach ($e -> prop as $propName => $value) {
          if($propName == "text"){
            $child -> nodeValue = $value;
          }
          else $child -> setAttribute($propName,$value);
        }
      }

      if(property_exists($e , 'proto')){
        foreach ($e -> proto as $protoName => $value) {
          try {
            // var_dump($e,$value);
              $result = eval($value); // si écheque sur l'évaluation , c'est que c'est "sensé" être une fonction js
              $child -> setAttribute("v:".$protoName,$value);
          } catch (ParseError $error) {
            // var_dump(is_bool($value)," value : ",$value);
            // var_dump(filter_var($value, FILTER_VALIDATE_BOOLEAN),$value);

            // filter_var('false', FILTER_VALIDATE_BOOLEAN)
            if(is_bool($value)){
              if($value == false) $child -> setAttribute("v:".$protoName,"false");
              else $child -> setAttribute("v:".$protoName,"true");
            }
            else if(Is_Numeric($value))$child -> setAttribute("v:".$protoName,$value);
            else if(explode("(",$value)[0] != "function") $child -> setAttribute("v:".$protoName,$value);
            else $child -> setAttribute("f:".$protoName,$value);
          }
        }
      }

      if(property_exists($e,'childrens')){

        $f = function(){};

        // var_dump($e -> childrens -> ui);
        // var_dump(gettype ($e -> childrens));
        // if($e -> ClassName != "UI" || $e -> ClassName != "UI")$e -> childrens = $e -> childrens();
        // var_dump(gettype ($e -> childrens));
        $e -> childrens -> renderIn($doc , $child);
      }

      if(!property_exists($child , 'th')){
        // $child -> th = array(
        //   "test" => "test"
        // );
        // new th($child);
        // var_dump($child);
      }

      $parent -> appendChild($child);
    }
    // echo $doc->saveXML();
  }

  private function normalize($value , $root , $parentNode){
    try {
        if(!is_array($value)){
          if(property_exists($value , "ClassName"))$value = (array)$value -> ui;
          else throw new Exception("definition n'est pas un tableau ni une class UI");
        }
        if(!is_array($value))throw new Exception("value n'est pas un tableau.");
        $i = 0;
        foreach ($value as $e) {
          $value[$i] = new UIelement($e , $root , $parentNode);
          $i++;
        }
    }
    catch (exception $e) {
        echo 'exception: ',  $e->getMessage(), "\n";
    }
    return $value;
  }

}

/*
*
*/
class Thorium {

  function components($arg){
    return new ThoriumComponents($arg);
  }

  function __construct() {
    // print "In constructor\n";
  }

  function __destruct() {
      // print "Destroying " . __CLASS__ . "\n";
  }

  public function CreateNewHTMLDocument($Headertemplates = null , $HTMLtemplate = null , $Scriptstemplates = null){

    $doc = new DomDocument();
    $html;
    if(!$HTMLtemplate)$html = "
      <!DOCTYPE html>
      <html>

        <head>
          <meta charset='UTF-8'/>
          <meta http-equiv='content-type' content='text/html;charset=UTF-8'/>
        </head>
        <body>
        </body>
      </html>
    ";
    else $html = $HTMLtemplate;

    $doc->validateOnParse = true; //<!-- this first
    $doc->loadHTML($html);        //'cause 'load' == 'parse
    $doc->preserveWhiteSpace = false;

    if(!$Headertemplates){

      // foreach ($Headertemplate as $key => $value) {
      //   $child = $doc->createElement($e -> type);
      // }

    }
    // print(is_null($Scriptstemplates));
    if(!is_null($Scriptstemplates)){
      // var_dump($Scriptstemplates);
      foreach (array_reverse($Scriptstemplates) as $tagName => $value) {
        // var_dump($value);
        $element = $doc->createElement("script");
        foreach ($value as $attribute => $str) {
          $element->setAttribute($attribute,$str);
        }
        // var_dump($element);
        $body = $doc -> getElementsByTagName("body") -> item(0);
        $body -> parentNode -> insertBefore( $element , $body -> nextSibling);
      }

    }


    return $doc;
  }

  public function addCss($doc , $css_id , $definition){
    $head = $doc -> getElementsByTagName("head") -> item(0);
    $style = $doc->createElement("style");
    $style->setAttribute("id" , $css_id);
    $style -> nodeValue = trim(preg_replace('/\s\s+/', ' ', $definition));
    $head -> appendChild($style);
  }

  public function GUI($ui){
    $this -> ui = new UI($ui);
  }

  public function render($doc , $parent){
    $this -> ui -> renderIn($doc , $parent);
  }

}

/*
*
*/
class thcomponents extends uielement {

    public function __construct($arg) {
      parent::__construct($arg);
    }

    function __destruct() {
        print "Destroying " . __CLASS__ . "\n";
    }
}

//
$GLOBALS["thorium"] = new Thorium();

//
// $tElement1 = new uielement(array(
//       "type" => "lol",
//       "prop" => array(
//         "text" => "lol1"
//       ),
//       "childrens" => array(
//         array(
//           "type" => "p",
//           "prop" => array(
//             "text" => "bloublou"
//           )
//         )
//       )
//     )
//   );
//
//   $tElement2 = new uielement(array(
//         "type" => "lol",
//         "prop" => array(
//           "text" => "lol2"
//         )
//       )
//     );
// // print_r($tElement);
?>
