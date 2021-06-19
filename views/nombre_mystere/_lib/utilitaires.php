<?php

$generalBaseUrl = "./views/nombre_mystere/";

include_once($generalBaseUrl . "index.php");

if (!defined("CHARSET_ANSI")) define("CHARSET_ANSI", "iso-8859-1");
if (!defined("CHARSET_UTF8")) define("CHARSET_UTF8", "utf-8");
if (!Php_SessionPresente()) return false;

function Php_SessionPresente()
{
	return isset($_SESSION) || @session_start();
}

function Php_SessionContient($contenu)
{
	if (!Php_SessionPresente() || !is_array($_SESSION)) return false;
	if (is_string($contenu))
	{
		return isset($_SESSION[$contenu]);
	}
	else if (is_array($contenu))
	{
		foreach ($contenu as $element)
		{
			if (is_string($element))
			{
				if (!isset($_SESSION[$element])) return false;
			}
			else if (is_array($element))
			{
				// $contenu = array("bidule", array("jeu", "partie_en_cours", "nombre_tentatives"))
				// $element = array("jeu", "partie_en_cours", "nombre_tentatives")
				// ? $_SESSION["jeu"]["partie_en_cours"]["nombre_tentatives"] ?
				$TesterRecursivement = function ($fonctionRecursive, &$tableau, &$nomsSousElements, $indiceNomSE)
				{
					if ($indiceNomSE == count($nomsSousElements)) return true;
					$nomSousElement = $nomsSousElements[$indiceNomSE];
					if (!is_string($nomSousElement) && !is_int($nomSousElement)) return false;
					if (!is_array($tableau) || !isset($tableau[$nomSousElement])) return false;
					return $fonctionRecursive($fonctionRecursive, $tableau[$nomSousElement], $nomsSousElements, $indiceNomSE + 1);
				};
				if (!$TesterRecursivement($TesterRecursivement, $_SESSION, $element, 0)) return false;
			}
			else
			{
				return false;
			}
		}
		return true;
	}
	else
	{
		return false;
	}
}

function Mail_Envoyer($jeuCaracteres = null, $adresseDestinataire = null, $adresseSource = null, $adresseReponse = null, $sujet = null, $contenu = null)
{
	if (($jeuCaracteres !== CHARSET_ANSI) && ($jeuCaracteres !== CHARSET_UTF8)) return false;
	if (!is_string($adresseDestinataire) || !is_string($sujet) || !is_string($contenu)) return false;
	if (!is_string($adresseSource))
	{
		$adresseSource = ini_get("sendmail_from");
		if (!is_string($adresseSource)) $adresseSource = $adresseReponse;
		if (!is_string($adresseSource)) return false;
	}
	if (!is_string($adresseReponse))
	{
		$adresseReponse = $adresseSource;
		if (!is_string($adresseReponse)) return false;
	}
	if (@mail($adresseDestinataire, $sujet, $contenu,
				  "From: $adresseSource\r\n"
				. "Reply-To: $adresseReponse\r\n"
				. "X-Mailer: Microsoft Outlook 15.0\r\n"
				. "Content-type: text/html; charset=$jeuCaracteres\r\n"
				)) return true;
	return false;
}

function Html_Contenu($texte)
{
	if (!is_string($texte)) return "";
	return str_replace(array("<", ">"), array("&lt;", "&gt;"), $texte);
}

function Html_Attribut($texte)
{
	if (!is_string($texte)) return "";
	return str_replace(array("<", ">", "\""), array("&lt;", "&gt;", "&quot;"), $texte);
}

function Http_Rediriger($url)
{
	if (!is_string($url)) return false;
	header("location:$url");
	die();
}

function Http_RedirigerVersReferant()
{
	if (($urlReferant = Http_UrlReferant()) === false) return false;
	if (($urlInterrogee = Http_UrlInterrogee()) === false) return false;
	$partiesDuReferant = explode("/", $urlReferant);
	$partiesInterrogees = explode("/", $urlInterrogee);
	$indiceFichierReferant = count($partiesDuReferant) - 1;
	$indiceFichierInterroge = count($partiesInterrogees) - 1;
	for ($indice = 2; true; $indice++)
	{
		if ($indice == $indiceFichierReferant)
		{
			if ($indice == $indiceFichierInterroge)
			{
				// $urlReferant   : 'http://localhost/pdweb/site_dynamique/index.php'
				// $urlInterrogee : 'http://localhost/pdweb/site_dynamique/authentifier.php'
				return Http_Rediriger("./$partiesDuReferant[$indiceFichierReferant]");
			}
			else
			{
				// $urlReferant   : 'http://localhost/pdweb/site_dynamique/index.php' (count=6 ; indiceFichier=5 ; indice=5)
				// $urlInterrogee : 'http://localhost/pdweb/site_dynamique/traitement/authentifier.php' (count=7 ; indiceFichier=6; indice=5)
				return Http_Rediriger(str_repeat("../", $indiceFichierInterroge - $indice) . $partiesDuReferant[$indiceFichierReferant]);
			}
		}
		else if ($indice == $indiceFichierInterroge)
		{
			// $urlReferant   : 'http://localhost/pdweb/site_dynamique/affichage/index.php' (count=7 ; indiceFichier=6 ; indice=5)
			// $urlInterrogee : 'http://localhost/pdweb/site_dynamique/authentifier.php' (count=6 ; indiceFichier=5; indice=5)
			return Http_Rediriger(implode("/", array_slice($partiesDuReferant, $indice)));
		}
		else if ($partiesDuReferant[$indice] != $partiesInterrogees[$indice])
		{
			// $urlReferant   : 'http://localhost/pdweb/site_dynamique/affichage/visiteur/index.php' (count=8 ; indiceFichier=7 ; indice=5)
			// $urlInterrogee : 'http://localhost/pdweb/site_dynamique/traitement/utilisateur/authentifier.php' (count=8 ; indiceFichier=7; indice=5)
			return Http_Rediriger(str_repeat("../", $indiceFichierInterroge - $indice) . implode("/", array_slice($partiesDuReferant, $indice)));
		}
	}
}

function Http_UrlReferant()
{
	return isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : false;
}

function Http_UrlInterrogee()
{
	$prefixe = isset($_SERVER["HTTP_ORIGIN"]) ? $_SERVER["HTTP_ORIGIN"] : (isset($_SERVER["REQUEST_SCHEME"], $_SERVER["HTTP_HOST"]) ? "$_SERVER[REQUEST_SCHEME]://$_SERVER[HTTP_HOST]" : false);
	if ($prefixe === false) return false;
	foreach (array("REQUEST_URI", "SCRIPT_NAME", "PHP_SELF") as $nomElement)
	{
		if (isset($_SERVER[$nomElement])) return $prefixe . $_SERVER[$nomElement];
	}
	return false;
}

/*
'HTTP_REFERER' => string 'http://localhost/pdweb/site_dynamique/index.php'

 'HTTP_ORIGIN' => string 'http://localhost'
   'REQUEST_SCHEME' => string 'http'
   'HTTP_HOST' => string 'localhost'
 'REQUEST_URI' => string '/pdweb/site_dynamique/traitement/authentifier.php' (length=49)
 'SCRIPT_NAME' => string '/pdweb/site_dynamique/traitement/authentifier.php' (length=49)
 'PHP_SELF' => string '/pdweb/site_dynamique/traitement/authentifier.php' (length=49)
*/

?>
