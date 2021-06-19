<?php
  class APP extends thcomponents{
    function __construct(){
      parent::__construct(array(
        "type"=>"app",
        "prop" => array(
          "id" => "app"
        ),
        "childrens" => array(
          array(
            "type" => "container",
            "prop" => array(
              "id" => "app-container"
            ),
            "childrens" => array(
              new MAIN(),
              new GAMEBOARD(),
            )
          )
        )
      ));
    }

    function __destruct(){

    }
  }

  $thorium -> addCss($docHTML , "app-style" , "
	 #app {
		 position: absolute;
		 height:100%;
		 width:100%;
		 top:0;
		 left:0;
		 display:grid;
     grid-template-columns: minmax(0,1fr);
     grid-template-rows: minmax(0,1fr);
		 z-index:10;
     font-family : Menlo, Consolas, DejaVu Sans Mono, monospace;
     font-size : 12px;
	 }

   app > container {
       margin: auto;
       display: grid;
       grid-column: 1;
       grid-row: 1;
       height: 100%;
       width: 100%;
       grid-template-columns: minmax(0,1fr);
       grid-template-rows: minmax(0,1fr);
       background-image: linear-gradient( -135deg , lightgray, gray);
   }

   app > container:after {
       content: 'Thorium Framework github.com/MortallicaXxX/ThoriumJS ';
       font-size:0.8vw;
       grid-column: 1;
       grid-row: 1;
       height: min-content;
       width: min-content;
       text-align: center;
       margin: auto 1vw 1vw auto;
       opacity: 0.5;
       color: white;
   }

   app > container:before {
     content: 'test';
     grid-column:1;
     grid-row:1;
     height:100%;
     width:100%;
     background-image : url(https://pixabay.com/static/uploads/photo/2014/06/16/23/39/black-370118_960_720.png);
     background-repeat : no-repeat;
     background-size : cover;
     background-position : center;
     opacity:0.5;
   }

   app > container > *{
      display: grid;
      grid-column: 1;
      grid-row: 1;
      grid-template-columns: 1fr;
      grid-template-rows: 1fr;
	 }
	 ");
?>
