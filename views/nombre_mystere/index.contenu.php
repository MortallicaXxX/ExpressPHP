<?php
//include_once("index.php");
$generalBaseUrl = "./views/nombre_mystere/";

Php_Inclure($generalBaseUrl . "_lib/utilitaires.php");
Php_Inclure($generalBaseUrl . "inc/page.php");

Menu_Definir("accueil");

if (Php_SessionPresente()) Page_Creer(null, null, "js/accueil.js", function ()
{
	/*
	var_dump($_SESSION, Php_SessionContient(array("bidule", array("truc", "machin", "chose"), "bidule 2", "bidule 3")));
	if (!isset($_SESSION["bidule"]))
	{
		$_SESSION["bidule"] = "une information";
	}
	else if (!isset($_SESSION["bidule 2"]))
	{
		$_SESSION["bidule 2"] = "une information";
	}
	else if (!isset($_SESSION["truc"]))
	{
		$_SESSION["truc"] = "une information";
	}
	else if (!is_array($_SESSION["truc"]))
	{
		$_SESSION["truc"] = array("sous-truc" => array("chose" => "chose"));
	}
	else if (!isset($_SESSION["truc"]["machin"]))
	{
		$_SESSION["truc"]["machin"] = array("element1" => 1, "chose" => "chose", "element2" => 2);
	}
	else if (!isset($_SESSION["bidule 3"]))
	{
		$_SESSION["bidule 3"] = "une information";
	}
	*/

	print("\t\t\t<p>Vous �tes sur la page d'accueil...</p>\r\n");
	if (Utilisateur_TesterAcces("V"))
	{
		print("\t\t\t<form action=\"traitement/authentifier.php\" method=\"post\"><ul>\r\n");

		$valeur = isset($_SESSION["formulaires"], $_SESSION["formulaires"]["authentification"], $_SESSION["formulaires"]["authentification"]["mot_de_passe"], $_SESSION["formulaires"]["authentification"]["mot_de_passe"]["valeur"]) ? $_SESSION["formulaires"]["authentification"]["mot_de_passe"]["valeur"] : false;
		$erreur = isset($_SESSION["formulaires"], $_SESSION["formulaires"]["authentification"], $_SESSION["formulaires"]["authentification"]["mot_de_passe"], $_SESSION["formulaires"]["authentification"]["mot_de_passe"]["erreur"]) ? $_SESSION["formulaires"]["authentification"]["mot_de_passe"]["erreur"] : false;
		print("\t\t\t\t<li><label for=\"mot_de_passe\">Mot de passe :</label><div><input type=\"password\" id=\"mot_de_passe\" name=\"mot_de_passe\""
			. (($valeur !== false) ? " value=\"" . Html_Attribut($valeur) . "\"" : "")
			. " required/>"
			. (($erreur !== false) ? "<br/><span class=\"erreur\">" . Html_Contenu($erreur) . "</span>" : "")
			. "</div></li>\r\n");

		print("\t\t\t\t<li><label></label><input type=\"submit\" value=\"S'authentifier en tant qu'administrateur\"/></li>\r\n");
		print("\t\t\t</ul></form>\r\n");

		print("\t\t\t<form action=\"/php/traitement/poster\" method=\"post\"><ul>\r\n");

		$valeur = false;
		$erreur = false;
		print("\t\t\t\t<li><label for=\"texte\">Texte � poster :</label><div><input type=\"text\" id=\"texte\" name=\"texte\""
			. (($valeur !== false) ? " value=\"" . Html_Attribut($valeur) . "\"" : "")
			. " required/>"
			. (($erreur !== false) ? "<br/><span class=\"erreur\">" . Html_Contenu($erreur) . "</span>" : "")
			. "</div></li>\r\n");

		print("\t\t\t\t<li><label></label><input type=\"submit\" value=\"Poster le texte ci-dessus\"/></li>\r\n");
		print("\t\t\t</ul></form>\r\n");


		$generalBaseUrl = "./views/nombre_mystere/";
		if (file_exists($generalBaseUrl . "donnees.txt"))
		{
			$fichier = @fopen("donnees.txt", "rt");
			if ($fichier !== false)
			{
				$premiereLigne = true;
				while (!feof($fichier))
				{
					$ligne = fgets($fichier);
					//$ligne = str_replace("\n", "", $ligne);
					$ligne = rtrim($ligne);
					if ($ligne != "")
					{
						if ($premiereLigne)
						{
							print("\t\t\t<table>");
							$premiereLigne = false;
						}
						print("<tr><td>" . Html_Contenu($ligne) . "</td></tr>");
					}
				}
				fclose($fichier);
				if (!$premiereLigne) print("</table>\r\n");
				//$contenu = @file_get_contents("donnees.txt");
			}
		}
	}
	else
	{
		print("\t\t\t<p><span>Bienvenue Administrateur</span><a href=\"traitement/deconnecter.php\">Se d�connecter</a></p>\r\n");
	}
});
?>
