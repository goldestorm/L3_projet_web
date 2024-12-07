<!DOCTYPE HTML>
<!--
	site de competition animal crossing de Dorian Blondel 28/09/2024
-->
<?php
    $mysqli = new mysqli('localhost','goldestorm','_W[XmrOlx1HS30zD','goldestorm');
    if ($mysqli->connect_errno){// Affichage d'un message d'erreur
        echo "Error: Problème de connexion à la BDD \n";
        echo "Errno: " . $mysqli->connect_errno . "\n";
        echo "Error: " . $mysqli->connect_error . "\n";// Arrêt du chargement de la page
        exit();
    }// Instructions PHP à ajouter pour l'encodage utf8 du jeu de caractères
        if (!$mysqli->set_charset("utf8")) {
            printf("Pb de chargement du jeu de car. utf8 : %s\n", $mysqli->error);
            exit();
    }
    //echo ("Connexion BDD réussie !")
?>
<html lang="fr">
	<head>
		<title>Dopetrope by HTML5 UP</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="../assets/css/main.css" />
	</head>
	<body class="homepage is-preload">
		<div id="page-wrapper">
			<!-- Header -->
				<section id="header">
					<!-- Logo -->
						<h1><a href="../index.php">Animal Crossing</a></h1>
					<!-- Nav -->
						<nav id="nav">
							<ul>
								<li ><a href="../index.php">Home</a></li>
								<li><a href="...">Concours</a></li>
								<li><a href="...">Jury</a></li>
								<li class="current"><a href="session.php">Connection</a></li>
							</ul>
						</nav>
					<!-- Banner -->
						<section id="banner">
							<header>
								<section>
									<form action="session_action.php" method="post">
										<fieldset>
										<legend style="color:white;">Veuillez saisir votre pseudo et votre mot de passe :</legend>
										<p style="color:white;">Votre pseudo :
											<input type="text" name="pseudo" placeholder="pseudo" required="required" />
										</p>
										<p style="color:white;">Votre mot de passe :
											<input type="password" name="mdp" placeholder="mot de passe" required="required" />
										</p>
											<p style="color:white;"><input type="submit" value="Valider"></p>
										</fieldset>
										</form>
								</section>	
							</header>
						</section>
		</div>
		<!-- Scripts -->
			<script src="../assets/js/jquery.min.js"></script>
			<script src="../assets/js/jquery.dropotron.min.js"></script>
			<script src="../assets/js/browser.min.js"></script>
			<script src="../assets/js/breakpoints.min.js"></script>
			<script src="../assets/js/util.js"></script>
			<script src="../assets/js/main.js"></script>

	</body>
</html>