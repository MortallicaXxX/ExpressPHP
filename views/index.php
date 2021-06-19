<?php

	include "./views/thorium.php"; // lib thorium

	/*
	@{desc} Crée un == thoriumDocument ==
		OBLIGATOIRE afin d'avoir un document compatible à l'utilisation des components.
		Toute les valeurs par défault sont à null , càd CreateNewHTMLDocument() renvois déjà un nouveau document utilisable QUI peut être custom en fonction des params suivant :
			* @{param1} list<list> contenant les links du document
			* @{param2} list<list> contenant le template HTML du document
			* @{param3} list<list> contenant les scripts du document
	*/
	$docHTML = $thorium -> CreateNewHTMLDocument(null,null,array(
		"script_0" => array("src" => "./thorium-compiled.js","type" => "text/javascript"),
		"script_1" => array("src" => "./run.js","type" => "text/javascript")
	));
	
	// Liste des components thorium
	include "./views/components/component.main.php"; // page principale
	include "./views/components/main/component.index.php"; // page principale
	include "./views/components/main/component.boutton.php"; // page principale
	include "./views/components/gameboard/component.gameboard.php"; // page principale

	/* definition du "General User Interface" */
	$thorium -> GUI(array(
		new APP()
	));

	/* render de GUI */
	$thorium -> render($docHTML , $docHTML->getElementsByTagName('body') -> item(0));

	/* sauvegarde des modification effectuée sur le document */
	echo $docHTML->saveHTML();

?>
