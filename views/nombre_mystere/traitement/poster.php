<?php
include_once("index.php");
Php_Inclure("../_lib/utilitaires.php");
Php_Inclure("inc/page.php");

if (Utilisateur_TesterAcces(array("V", "A")))
{
	if (isset($_POST["texte"]))
	{
		$texte = trim($_POST["texte"]);
		if (($fichier = @fopen("../donnees.txt", "at")) !== false)
		{
			fputs($fichier, "$texte\n");
			fclose($fichier);
			//@file_put_contents("../donnees.txt", "Bonjour\r\nComment allez-vous ?\r\nAu revoir");
			//@file_put_contents("../donnees.txt", "$texte\n", FILE_APPEND);
		}
		//unset($fichier);
	}
}
Http_Rediriger("./");
?>