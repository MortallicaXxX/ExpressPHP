<?php
include_once("index.php");
Php_Inclure("../_lib/utilitaires.php");
Php_Inclure("inc/page.php");

if (!Utilisateur_TesterAcces("V"))
{
	Utilisateur_Definir("V");
	Http_Rediriger("./");
}
else
{
	Http_Rediriger("./");
}
?>