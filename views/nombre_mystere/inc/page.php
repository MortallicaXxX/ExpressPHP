<?php
$generalBaseUrl = "./views/nombre_mystere/";
include_once($generalBaseUrl . "index.php");
Php_Inclure($generalBaseUrl . "_lib/document.php");

// Code commun � toutes les pages "affich�es" du site

function Page_Creer($sousTitre = null, $css = null, $js = null, $generateurContenu = null)
{
	$generalBaseUrl = "./views/nombre_mystere/";
	foreach (array(
		"css" => array("base" => array("./res/base.css"), "complement" => $css),
		"js" => array("base"=> array("./js/application.js"), "complement" => $js)
	)
	as $cle => $etape)
	{
		$base = $etape["base"];
		$complement = $etape["complement"];
		if (is_string($complement)) $complement = array($complement);
		if (is_array($complement))
		{
			foreach ($complement as $url)
			{
				if (is_string($url) && !in_array($url, $base)) $base[] = $url;
			}
		}
		switch ($cle)
		{
			case "css":
				$css = $base;
				break;
			case "js":
				$js = $base;
				break;
		}
	}
	$titre = "Site PDWEB" . (is_string($sousTitre) && !empty($sousTitre) ? " - " . $sousTitre : "");
	$contexte = array("titre" => $titre, "contenu" => $generateurContenu);
	Document_Creer(
		CHARSET_ANSI,
		Html_Contenu($titre),
		$css,
		$js,
		function (&$contexte)
		{
			print("\t\t<header>\r\n");
			if (is_array($contexte) && isset($contexte["titre"]) && is_string($contexte["titre"]))
			{
				print("\t\t<h1>" . Html_Contenu($contexte["titre"]) . "</h1>\r\n");
			}
			Menu_Afficher();
			print("\t\t</header>\r\n");
			print("\t\t<main>\r\n");
			if (is_array($contexte) && isset($contexte["contenu"]) && is_callable($contexte["contenu"])) $contexte["contenu"]();
			print("\t\t</main>\r\n");
		},
		$contexte);
}

function Utilisateur_Definir($typeUtilisateur)
{
	if (!Php_SessionPresente()) return false;
	if (!is_string($typeUtilisateur) || !in_array($typeUtilisateur, array("V", "A"))) return false;
	$_SESSION["typeUtilisateur"] = $typeUtilisateur;
	return true;
}

function Utilisateur_TypeAuthentifie()
{
	return Php_SessionContient("typeUtilisateur") ? $_SESSION["typeUtilisateur"] : "V";
}

function Utilisateur_TesterAcces($acces)
{
	if (!Php_SessionPresente()) return false;
	if (!Php_SessionContient("typeUtilisateur")) Utilisateur_Definir("V");
	if (is_string($acces))
	{
		return (strpos($acces, $_SESSION["typeUtilisateur"]) !== false);
	}
	else if (is_array($acces))
	{
		return in_array($_SESSION["typeUtilisateur"], $acces);
	}
	return false;
}

function Menu_PremierePageAccessible($acces)
{
	if (!Php_SessionContient("menu")) return false;
	for ($etape = 1; $etape <= 2; $etape++)
	{
		if (is_string($acces))
		{
			foreach ($_SESSION["menu"] as $codeElement => $element)
			{
				for ($indice = 0, $longueur = strlen($acces); $indice < $longueur; $indice++)
				{
					$unAcces = substr($acces, $indice, 1);
					if (is_array($element["acces"]))
					{
						if (in_array($unAcces, $element["acces"]) && (($etape == 2) || !in_array("V", $element["acces"]))) return $codeElement;
					}
					else if (is_string($element["acces"]))
					{
						if ((strpos($element["acces"], $unAcces) !== false) && (($etape == 2) || (strpos($element["acces"], "V") === false))) return $codeElement;
					}
				}
			}
		}
		else if (is_array($acces))
		{
			foreach ($_SESSION["menu"] as $codeElement => $element)
			{
				foreach ($acces as $unAcces)
				{
					if (is_array($element["acces"]))
					{
						if (in_array($unAcces, $element["acces"]) && (($etape == 2) || !in_array("V", $element["acces"]))) return $codeElement;
					}
					else if (is_string($element["acces"]))
					{
						if ((strpos($element["acces"], $unAcces) !== false) && (($etape == 2) || (strpos($element["acces"], "V") === false))) return $codeElement;
					}
				}
			}
		}
	}
	return false;
}

function Menu_UrlPoint($codeElement)
{
	if (!is_string($codeElement) || !Php_SessionContient(array(array("menu", $codeElement, "url")))) return false;
	return $_SESSION["menu"][$codeElement]["url"];
}

function Menu_Definir($pointActif)
{
	if (!Php_SessionPresente()) return false;
	if (!isset($_SESSION["menu"]))
	{
		$_SESSION["menu"] = array
		(
			"accueil" => array
			(
				"code" => "accueil",
				"libelle" => "Accueil",
				"url" => "index.php",
				"acces" => "VA"
			),
			"contact" => array
			(
				"code" => "contact",
				"libelle" => "Contactez-nous",
				"url" => "contact.php",
				"acces" => "VA"
			),
			"administration" => array
			(
				"code" => "administration",
				"libelle" => "Administration du site",
				"url" => "administration.php",
				"acces" => "A"
			)
		);
	}
	if (is_string($pointActif) && Php_SessionContient(array(array("menu", $pointActif, "acces"))))
	{
		if (Utilisateur_TesterAcces($_SESSION["menu"][$pointActif]["acces"]))
		{
			$_SESSION["pointActifMenu"] = $pointActif;
		}
		else
		{
			Http_Rediriger("./");
		}
	}
	else if (!Php_SessionContient("pointActifMenu"))
	{
		$_SESSION["pointActifMenu"] = "accueil";
	}
}

function Menu_Afficher()
{
	if (!Php_SessionContient("menu")) return false;
	print("\t\t\t<nav><ul>\r\n");
	foreach ($_SESSION["menu"] as $element)
	{
		if (Utilisateur_TesterAcces($element["acces"]))
		{
			if ($element["code"] == $_SESSION["pointActifMenu"])
			{
				print("\t\t\t\t<li><span class=\"point_actif\">$element[libelle]</span></li>\r\n");
			}
			else
			{
				print("\t\t\t\t<li><a href=\"$element[url]\">$element[libelle]</a></li>\r\n");
			}
		}
	}
	print("\t\t\t</ul></nav>\r\n");
	return true;
}
?>
