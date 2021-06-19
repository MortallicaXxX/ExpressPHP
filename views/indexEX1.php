<?php header("content-type:text/html;charset=iso-8859-1"); ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
		<meta http-equiv="content-type" content="text/html;charset=iso-8859-1"/>
    <title></title>
    <link rel="stylesheet" href="../ex1.style.css" mimetype="text/plain">
  </head>
  <body>
    <?php

    	print(template());

  		function template(){
  			return "<div id='container' name='index'>
          <form id='reglement'>
            <h1>Règlement</h1>
            <div id='regles-container'>
              <p>La première page (page d'accueil) :</p>
              <ul>
                <li>doit expliquer les règles du jeu</li>
                <li>permettre de démarrer une partie</li>
              </ul>

              <p>La page de démarrage d'une partie :</p>
              <ul>
                <li>doit inviter l'utilisateur à encoder son nom (et son prénom)</li>
                <li>doit indiquer que la valeur doit être comprise entre xxx et yyy</li>
                <li>doit inviter l'utilisateur à encoder sa première proposition</li>
              </ul>

              <p>Après validation de cette première page, la page de jeu suivante :</p>
              <ul>
                <li>doit indiquer, si la valeur encodée par l'utilisateur est incorrecte,
                si la valeur est trop petite ou trop grande, et dans ce cas,
                indiquer qu'il s'agit du zzz essai, et lui proposer
                d'encoder une autre valeur => il revient sur cette même page de jeu</li>
                <li>doit indiquer, si la valeur encodée par l'utilisateur est correcte,
                qu'il a trouvé le nombre mystère en zzz essai(s) ; son nom (et son
                prénom) devront être affiché en même temps que la valeur du nombre
                mystère et le nombre d'essais ; il doit disposer d'un moyen aisé
                de revenir sur la page d'accueil du site</li>
              </ul>
            </div>
            <a href='http://localhost:3000/php/indexEX1.jeux.php'><p>Envoyer</p></a>
          </form >
        </div>";
  		}
    ?>
  </body>
</html>
