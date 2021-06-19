<?php

class UIelement {

  $templates = array();
  $ui = array();
  $root;
  $parentNode;

  function __construct($value , $root = null , $parentNode = null) {
    $this -> uielement = $arg;
    if($root != null)$this -> root = $root;
    if($parentNode)$this -> parentNode = $parentNode;
    else $this -> parentNode = $this;
    $this -> ui = $this -> normalize(value , root , parentNode);
  }

  // constructor(value , root = null , parent = null){
  //   this.__proto__.ClassName = 'UI';
  //   if(root) this.__proto__.root = root;
  //   if(parent) this.__proto__.parent = parent;
  //   if(!parent) parent = this;
  //   this.ui = this.normalise(value , root , parent);
  //   Object.assign(this.templates, this.ui);
  // }

  function __destruct() {
      print "Destroying " . __CLASS__ . "\n";
  }
}

class UI {
  $templates = array();
  $ui = array();
  $root;
  $parentNode;

  function __construct($value , $root = null , $parentNode = null) {
    $this -> uielement = $arg;
    if($root != null)$this -> root = $root;
    if($parentNode)$this -> parentNode = $parentNode;
    else $this -> parentNode = $this;
    $this -> ui = $this -> normalize(value , root , parentNode);
  }

  function __destruct() {
      print "Destroying " . __CLASS__ . "\n";
  }

  function renderIn($parent , $template = null){
    // Create a new DOMDocument
    $domDocument = new DOMDocument('1.0', 'iso-8859-1');

    // Use createElement() function to add a new element node
    // $domElement = $domDocument->createElement($template -> type);
    foreach ($template -> prop as $key => $value) {
      print($key);
      print($value);
    }()

    // Append element to the document
    // $parent->appendChild($domElement);
  }
}

class ThoriumComponents {

    public $uielement;

    function __construct($arg) {
      $this -> uielement = $arg;
    }

    function __destruct() {
        print "Destroying " . __CLASS__ . "\n";
    }

    function render(parent) {
      // Create a new DOMDocument
      $domDocument = new DOMDocument('1.0', 'iso-8859-1');

      // Use createElement() function to add a new element node
      $domElement = $domDocument->createElement('organization', 'GeeksforGeeks');

      // Append element to the document
      $parent->appendChild($domElement);

      // Save XML file and display it
      echo $domDocument->saveXML();
        print $this -> uielement;
    }
}

class Thorium {

  public $components(arg){
    return new ThoriumComponents(arg);
  }

  function __construct() {
    print "In constructor\n";
  }

  function __destruct() {
      print "Destroying " . __CLASS__ . "\n";
  }

}

$thorium = new Thorium();

class tElement extends thorium.components{

  function __construct(arg) {
    parent::__construct(
          array(
            "type" => "div",
            "prop" => array(
              "text" => "test"
            )
        )
      )
  }

  function __destruct() {
      print "Destroying " . __CLASS__ . "\n";
  }

}

echo('
  <!DOCTYPE html>
  <html lang="en" dir="ltr">
    <head>
      <meta charset="utf-8">
      <title></title>
    </head>
    <body>
      <?php
        
      ?>
    </body>
  </html>
')

?>
