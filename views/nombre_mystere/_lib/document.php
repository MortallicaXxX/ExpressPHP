<?php
$generalBaseUrl = "./views/nombre_mystere/";
include_once($generalBaseUrl . "index.php");
Php_Inclure($generalBaseUrl . "_lib/utilitaires.php");

if (!defined("CHARSET_ANSI")) define("CHARSET_ANSI", "iso-8859-1");
if (!defined("CHARSET_UTF8")) define("CHARSET_UTF8", "utf-8");


function Document_Creer($jeuCaracteres = null, $titre = null, $css = null, $js = null, $generateurContenu = null, &$contexte = null)
{
	$generalBaseUrl = "./views/nombre_mystere/";
	if (($jeuCaracteres !== CHARSET_ANSI) && ($jeuCaracteres !== CHARSET_UTF8)) return false;
	header("content-type:text/html;charset=$jeuCaracteres");
	print("<!doctype html>\r\n\r\n<html>\r\n\r\n\t<head>\r\n\t\t<meta charset=\"$jeuCaracteres\"/>\r\n\t\t<meta http-equiv=\"content-type\" content=\"text/html;charset=$jeuCaracteres\"/>\r\n");

	if (is_string($titre) && !empty($titre))
	{
		print("\t\t<title>" . Html_Contenu($titre) . "</title>\r\n");
	}

	if (is_string($css)) $css = array($css);
	if (is_string($js)) $js = array($js);

	if (is_array($css))
	{
		foreach ($css as $url)
		{
			if (is_string($url) && file_exists($generalBaseUrl . $url))
			{
				print("\t\t<link rel=\"stylesheet\" href=\"$url\"/>\r\n");
			}
		}
	}

	if (is_array($js))
	{
		foreach ($js as $url)
		{
			if (is_string($url) && file_exists($generalBaseUrl . $url))
			{
				print("\t\t<script src=\"$url\" type=\"application/javascript\" charset=\"$jeuCaracteres\"></script>\r\n");
			}
		}
	}

	print("\t</head>\r\n\r\n\t<body>\r\n");

	if (is_callable($generateurContenu))
	{
		$generateurContenu($contexte);
	}

	print("\t</body>\r\n\r\n</html>\r\n");
}
?>
