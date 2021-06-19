<?php
$generalBaseUrl = "./views/nombre_mystere";
include_once($generalBaseUrl . "/index.php");
Php_Inclure($generalBaseUrl . "/_lib/utilitaires.php");
Php_Inclure($generalBaseUrl . "/inc/page.php");

Menu_Definir("administration");

Page_Creer(null, null, null, function ()
{
	?>
	<p>Comme vous pouvez vous en rendre compte, cette page est accessible uniquement � une personne identifi�e comme administrateur.</p>
	<p>Ceci ne peut �tre vu que par un administrateur !</p>
	<?php
});
?>
