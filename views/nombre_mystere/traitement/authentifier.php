<?php
include_once("index.php");
Php_Inclure("../_lib/utilitaires.php");
Php_Inclure("inc/page.php");

if (Utilisateur_TesterAcces("V"))
{
	if (isset($_POST["mot_de_passe"]))
	{
		if ($_POST["mot_de_passe"] == "secret")
		{
			if (isset($_SESSION["formulaires"])) unset($_SESSION["formulaires"]["authentification"]);
			Utilisateur_Definir("A");
			$url = Menu_UrlPoint(Menu_PremierePageAccessible(Utilisateur_TypeAuthentifie()));
			if ($url !== false) Http_Rediriger("../$url");
		}
		else
		{
			if (Php_SessionPresente())
			{
				$_SESSION["formulaires"]["authentification"]["mot_de_passe"]["valeur"] = $_POST["mot_de_passe"];
				$_SESSION["formulaires"]["authentification"]["mot_de_passe"]["erreur"] = "Mot de passe incorrect !";
			}
			Http_RedirigerVersReferant();
		}
	}
}
Http_Rediriger("./");
?>