<?php
  class MAIN extends thcomponents{
    function __construct(){
      parent::__construct(array(
        "type"=>"main",
        "prop" => array(
          "id" => "main-page",
        ),
        "childrens" => array(
          array(
            "type" => "container",
            "prop" => array(
              "id" => "main-container"
            ),
            "childrens" => array(
              new BouttonMain('Nouvelle partie','function(self){
                document.getElementById("gameboard").turnActive();
              }'),
              new BouttonMain('Tableau des scores'),
              new BouttonMain('Explications','function(){
                thorium.dialog.new({
                    title : "Explications",
                    modal: true,
                    html : `
                      <div class="container-tuto">
                        <div class="container">
                          <h1>Carré</h1>
                          <p>Carré est un jeux rapide qui a pour but de mettre le curseur sur le carré rouge le plus de fois dans le temps impartis.</p>
                          <p>Les principes du carré sont les suivant :</p>
                          <ul>
                            <li>Echappement si le curseur est à une distance de 2 blocks ou moins.</li>
                            <li>Fuite dans une direction représentant la meilleur alternative pour la fuite actuelle ou pour une éventuelle autre fuite.</li>
                            <li>Evitement des blocks "Obstacles".</li>
                          </ul>
                          <p>Le temps pour réaliser le plus de passage sur le carré rouge est de 1:00 minute.</p>
                          <p>Pour chaques passage la distance d\'évitement augment ce qui augmente la difficultée d\'atteindre le carré.</p>
                          <p>Des blocks "obstacles" peuvent être mis sur le plateau de jeux et restrindre les possibilitées du carré.</p>
                        </div>
                      </div>
                    `,
                  });
              }'),
            )
          )
        )
      ));
    }

    function __destruct(){}
  }

  $thorium -> addCss($docHTML,'main_page-style',"
    main{
      display : grid;
      grid-template-columns : 1fr;
      grid-template-rows : 1fr;
    }

    main > container{
      display : grid;
      grid-template-columns: minmax(0,1fr);
      grid-template-rows: minmax(0,1fr) minmax(0,1fr) minmax(0,1fr);
    }

    main > container > boutton {
        height: 50%;
        max-height: 200px;
        width: 50%;
        margin: auto;
        display: grid;
        background-color: lightblue;
        border-radius: 0.5vw;
        filter: drop-shadow(1px 1px 1px royalblue);
        background-image: linear-gradient(45deg, rgba(0,0,255,0.5), rgba(0,0,255,0.2));
        color: white;
        font-weight: bold;
        font-size: 2vw;
    }

    .container-tuto {
      height: 100%;
      width: 100%;
      font-family: Menlo, Consolas, DejaVu Sans Mono, monospace;
      font-size: 14px;
    }

    .container-tuto > .container{
      height: 90%;
      width: 90%;
      margin: auto;
      margin-top: 5px;
    }

    .container-tuto > .container > h1{
      text-align : center;
    }
  ")
?>
