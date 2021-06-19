<?php
$generalBaseUrl = "./views/nombre_mystere/";
$redirection = false;
$inclusionContenu = false;
if (count(get_included_files()) == 1)
{
	// Ce fichier index.php est interrog� par le serveur http (suite � une demande de la part du client http)
	if (file_exists($generalBaseUrl . "index.contenu.php"))
	{
		$inclusionContenu = true; // on doit inclure le fichier de contenu de l'index puisqu'il existe
	}
	else
	{
		$redirection = true; // sinon, on remonte d'un r�pertoire par redirection http
	}
}
else
{
	// Ce fichier index.php est inclus par un autre fichier php (via une instruction include_once, include, require_once, require)
}
if ($redirection)
{
	header("location:../");
	die();
}
else
{
	if (!is_callable("Php_Inclure"))
	{
		function Php_Inclure($url)
		{
			if (file_exists($url)) include_once($url);
			else if (file_exists("../$url")) include_once("../$url");
			else die("<p>Erreur interne : chemin vers $url !</p>");
		}
	}
}
if ($inclusionContenu)
{
	unset($redirection, $inclusionContenu);
	include_once($generalBaseUrl . "index.contenu.php"); // ce fichier est cens� �tre pr�sent au niveau de la racine du site
}
else
{
	unset($redirection, $inclusionContenu);
}
?>
