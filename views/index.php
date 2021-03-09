<?php
header("content-type:text/html;charset=iso-8859-1");
?>
<!doctype html>

<html>

	<head>
		<meta charset="iso-8859-1"/>
		<meta http-equiv="content-type" content="text/html;charset=iso-8859-1"/>
	</head>

	<body>
		<h1>Histoire de PHP</h1>
		<p>Il �tait une fois du code PHP ???</p>
		<?php
		print("<p>Le code PHP permet de g&eacute;n�rer du <strong>contenu</strong> dans la page, et ce de mani�re &quot;dynamique&quot;</p>");
		/*
		$unePremiereVariable = 15;

		var_dump("unePremiereVariable", $unePremiereVariable);

		if (isset($bidule)) var_dump($bidule);

		//MaPremiereFonction();
		SecondeFonction();
		SecondeFonction();


		function MaPremiereFonction()
		{
			global $unePremiereVariable, $uneVariablePartagee;
			$uneAutreVariable = -456;
			$uneVariablePartagee = 11;
			var_dump($unePremiereVariable);
		}

		function SecondeFonction()
		{
			global $unePremiereVariable, $uneAutreVariable, $uneVariablePartagee;
			if (isset($uneVariablePartagee))
			{
				$uneVariablePartagee *= 2;
				var_dump($uneVariablePartagee);
			}
			var_dump($unePremiereVariable);
			var_dump($uneAutreVariable);
		}
		*/

		AfficherParagraphe("mon premier paragraphe");
		AfficherParagraphe("un autre paragraphe");
		AfficherParagraphe("et encore un autre paragraphe");
		MettreFinAuxParagraphes();
		AfficherParagraphe("un nouveau paragraphe");
		AfficherParagraphe("un nouveau paragraphe suppl�mentaire");

		var_dump(RechercherElement(array("un", 2, "trois", true, 5.2, 6, "sept"), "trois"));
		var_dump(RechercherElement(array("un", 2, "trois", true, 5.2, 6, "sept"), "Troie"));
		var_dump(RechercherElement(array("un", 2, "trois", true, 5.2, 6, "sept"), 6.0));
		var_dump(RechercherElement(array("un", 2, "trois", true, 5.2, 6, "sept"), true));

		var_dump("NUMERO PARAGRAPHE", $numeroParagraphe);

		unset($machin);


		$unTableau = array("un", 2, "trois", true, 5.2, 6, "sept");
		var_dump("unTableau", $unTableau);
		unset($unTableau[3]);
		var_dump("unTableau", $unTableau);
		var_dump(RechercherElement($unTableau, "sept"));

		function AfficherParagraphe($contenu)
		{
			global $numeroParagraphe;
			if (!isset($numeroParagraphe)) $numeroParagraphe = 1;
			print("<p>[");
			print($numeroParagraphe);
			print("]");
			print($contenu);
			print("</p>");
			$numeroParagraphe++;
		}

		function MettreFinAuxParagraphes()
		{
			global $numeroParagraphe;
			if (isset($numeroParagraphe))
			{
				var_dump($numeroParagraphe);
				unset($numeroParagraphe); // "suppression localement" de la variable globale numeroParagraphe ; c'est � dire que cela annule l'effet de l'instruction global
				var_dump(isset($numeroParagraphe));
				unset($GLOBALS["numeroParagraphe"]); // suppression effective d'une variable globale � partir d'une fonction
			}
		}

		function RechercherElement($tableau, $contenuRecherche)
		{
			var_dump("<p>","RECHERCHE", $tableau, $contenuRecherche,'</p>');
			//for ($i = 0; $i < count($tableau); $i++)
			//for ($i = 0; isset($tableau[$i]); $i++)
			for ($i = 0, $n = count($tableau); $i < $n; $i++)
			{
				if (!isset($tableau[$i]))
				{
					$n++;
				}
				else if ($tableau[$i] === $contenuRecherche)
				{
					$indiceElement = $i;
					break;
				}
			}
			if (isset($indiceElement))
			{
				$elementTrouve = $tableau[$indiceElement];
				unset($indiceElement);
			}
			else
			{
				$elementTrouve = null;
			}
			//...$elementTrouve...
			var_dump("<p>","isset(indiceElement) ?", isset($indiceElement),'</p>');
			return $elementTrouve;
		}
		?>
	</body>


</html>
