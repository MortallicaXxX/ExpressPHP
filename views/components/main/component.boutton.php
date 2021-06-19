<?php
	// include "./views/components/header/component.index.php";
  class BouttonMain extends thcomponents{
    function __construct($text,$f = "0"){
      parent::__construct(array(
        "type" => "boutton",
        "prop" => array(
          "class" => "menu_boutton",
        ),
        "childrens" => array(
          array(
            "type" => "p",
            "prop" => array("text" => $text)
          )
        ),
        "proto" => array(
          "f" => "({$f})",
          "onInitialise" => "function(self){
            // if(self.inBuildFunction.get() != ''){
            //   self.f.set( eval(self.f.get()) );
            // }
            console.log(self);
          }",
          "onMouseDown" => "function(self){
            eval(self.f.get())(self);
          }"
        )
      ));
    }

    function __destruct(){}
  }

  $thorium -> addCss($docHTML,'bouttonMain-style',"
    boutton {
      height: 50%;
      max-height: 200px;
      width: 50%;
      margin: auto;
      display : grid;
    }

    boutton > p {
      margin: auto;
    }
  ")
?>
