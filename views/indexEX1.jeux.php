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
  			return "<div id='container' name='jeux'>
          <form id='form-jeux'>
            <h1>Nombre mystère</h1>
            <div id='player-profil'>
              <p>Profil</p>
              <input name='nom' placeholder='nom'></input>
              <input name='prenom' placeholder='prénom'></input>
            </div>
            <div id='position-container'>
              <input id='position-input' name='position' placeholder='position entre 0 et 100' min='0' max='100' onkeyUp=isBornee(this)></input>
            </div>
            <div id='submit' onclick=submitNumber(document.getElementById('position-input').value)><p>Envoyer</p></div>
            <div id='essais-contaier'>
            </div>
          </form >
        </div>";
  		}
    ?>
  </body>

  <script type="text/javascript">

    function random(min = 0, max = 100){
      let num = Math.random() * (max - min) + min;
      return Math.floor(num);
    };

    function isBornee(e){
      let min = parseInt(e.getAttribute('min'));
      let max = parseInt(e.getAttribute('max'));
      if(e.value != '' && typeof Number(e.value) == 'number'){
        if(parseInt(e.value) < min || parseInt(e.value) > max){
          alert("La valeur n'est pas comprise entre les bornes 0 - 100")
        }
      }
    }

    var nombreMystere = random(0,100);
    var nombreEssais = 0;

    function submitNumber(value){

      try{
        if(value < 0 || value > 100)throw {err:1,msg:"Vous avez encodez un nombre trop grand , malgré l'alerte vous avez quand même essayer de valider le nombre , le resultat est une erreur"}
        if(value == nombreMystere){
          alert("Bravo vous avez trouver les nombre mystère");
          document.getElementById('essais-contaier').innerHTML += "<div class='EssaisContainer' type='succes'><p class='nombreEssais'>Essais "+nombreEssais+"</p><p class='position'>position "+value+"</p></div>";
        }else{
          document.getElementById('essais-contaier').innerHTML += "<div class='EssaisContainer'><p class='nombreEssais'>Essais "+nombreEssais+"</p><p class='position'>position "+value+"</p></div>";
        }
        nombreEssais++;
      }catch(err){
        document.getElementById('position-input').value = '';
        alert(err.msg);
      }
    }

  </script>

</html>
