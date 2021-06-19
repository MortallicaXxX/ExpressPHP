<?php
$generalBaseUrl = "./views/nombre_mystere";
include_once($generalBaseUrl . "/index.php");
Php_Inclure($generalBaseUrl . "/_lib/utilitaires.php");
Php_Inclure($generalBaseUrl . "/inc/page.php");

Menu_Definir("contact");

Page_Creer("Contact", null, null, function ()
{
	?>
	<p>Voici les modalit�s vous permettant d'entrer en contact avec nous...</p>
	<?php
});
?>
