<!DOCTYPE HTML>
<!--
	site de competition animal crossing de Dorian Blondel 28/09/2024
-->
<?php
							echo ("Page Web OK");
							// Connexion à la base MariaDB
							$mysqli = new mysqli('localhost','e22100290sql','2YUknMDj','e22100290_db1');
							if ($mysqli->connect_errno) {
							//...
							}
?>
<html lang="fr">
	<head>
		<title>Projets olympix</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="./assets/css/main.css" />
	</head>
	<body class="homepage is-preload">
		<div id="page-wrapper">

			<!-- Header -->
				<section id="header">

					<!-- Logo -->
						<h1><a href="index.html">Animal Crossing</a></h1>

					<!-- Nav -->
						<nav id="nav">
							<ul>
								<li class="current"><a href="index.html">Home</a></li>
								<li><a href="./connexion/session.php">Concours</a></li>
								<li><a href="right-sidebar.html">Jury</a></li>
								<li><a href="./connexion/session.php">Connexion</a></li>
							</ul>
						</nav>

					<!-- Banner -->
						<section id="banner">
							<header>
								<img src="images/Logo-acnh-removebg-preview.png" alt="" />
							</header>
						</section>
					
			<!-- Main -->
			<?php
							//Préparation du mot de passe de l’utilisateur tuxie
							$userspassword = "Dorian1234";

							// On rajoute du sel...
							// pour empêcher les attaques par "Rainbow Tables" cf
							// http://en.wikipedia.org/wiki/Rainbow_table

							$salt = "Ceciestmonmotdepasse";

							// Le mot de passe rallongé sera donc :
							// OnRajouteDuSelPourAllongerleMDP123!!45678__TestCeciEstMonMotdePasse!123
							$password = hash('sha256', $salt.$userspassword);
							echo $password;
							// Constitution par concaténation d'une requête UPDATE + exécution
							$requete = "UPDATE T_compte_cpt SET mot_de_passe_cpt='".$password."' WHERE
							email_cpt='williams@example.com';";
							echo($requete);
							$resultat=$mysqli->query($requete);
							/*Modification du mot de passe du profil de login tuxie*/
							if (!$resultat)
							{
								printf("erreur \n ");
							}
							else
							{
							// La requête a réussi...
								printf("requête reussi \n");
							}
							$sql = "SELECT * FROM T_document_doc WHERE id_document_doc IN(SELECT MAX(id_document_doc) FROM T_document_doc);";
							if ($result = $mysqli->query($sql)) {
							/* Détermination et affichage du nombre de lignes du résultat */
							$row_cnt = $result->num_rows;
							/* Récupération d'un tableau associatif */
							$row = $result->fetch_assoc();
							echo("<h1> ID =".$row['id_document_doc']."</h1>");
							echo("<h1> PHOTO =</h1>");
							echo("<br>");
							echo("<img src='./documents/".$row['description_doc']. "'/>");
							}
							?>
				<section id="main">
					<div class="container">
						<div class="row">
							<div class="col-12">
								<!-- Actualité -->
								<section>
									<header class="major">
										<h2>Actualités</h2>
									</header>
									<div class="row">
										<div class="col-4 col-6-medium col-12-small">
											<section class="box">
												<header>
													<h3>Ipsum feugiat et dolor</h3>
												</header>
											<p>Lorem ipsum dolor sit amet sit veroeros sed amet blandit consequat veroeros lorem blandit adipiscing et feugiat phasellus tempus dolore ipsum lorem dolore.</p>
										</section>
									</div>
									<div class="row">
										<div class="col-4 col-6-medium col-12-small">
											<section class="box">
												<header>
													<h3>Ipsum feugiat et dolor</h3>
												</header>
											<p>Lorem ipsum dolor sit amet sit veroeros sed amet blandit consequat veroeros lorem blandit adipiscing et feugiat phasellus tempus dolore ipsum lorem dolore.</p>
										</section>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>

			<!-- Footer -->
				<section id="footer">
					<div class="container">
						<div class="row">
							<div class="col-4 col-6-medium col-12-small">
								<section>
									<header>
										<h2>Tempus consequat</h2>
									</header>
									<ul class="divided">
										<li><a href="#">Lorem ipsum dolor sit amet sit veroeros</a></li>
										<li><a href="#">Sed et blandit consequat sed tlorem blandit</a></li>
										<li><a href="#">Adipiscing feugiat phasellus sed tempus</a></li>
										<li><a href="#">Hendrerit tortor vitae mattis tempor sapien</a></li>
										<li><a href="#">Sem feugiat sapien id suscipit magna felis nec</a></li>
										<li><a href="#">Elit class aptent taciti sociosqu ad litora</a></li>
									</ul>
								</section>
							</div>
							<div class="col-4 col-6-medium col-12-small">
								<section>
									<header>
										<h2>Ipsum et phasellus</h2>
									</header>
									<ul class="divided">
										<li><a href="#">Lorem ipsum dolor sit amet sit veroeros</a></li>
										<li><a href="#">Sed et blandit consequat sed tlorem blandit</a></li>
										<li><a href="#">Adipiscing feugiat phasellus sed tempus</a></li>
										<li><a href="#">Hendrerit tortor vitae mattis tempor sapien</a></li>
										<li><a href="#">Sem feugiat sapien id suscipit magna felis nec</a></li>
										<li><a href="#">Elit class aptent taciti sociosqu ad litora</a></li>
									</ul>
								</section>
							</div>
							<div class="col-4 col-12-medium">
								<section>
									<header>
										<h2>Vitae tempor lorem</h2>
									</header>
									<ul class="social">
										<li><a class="icon brands fa-facebook-f" href="#"><span class="label">Facebook</span></a></li>
										<li><a class="icon brands fa-twitter" href="#"><span class="label">Twitter</span></a></li>
										<li><a class="icon brands fa-dribbble" href="#"><span class="label">Dribbble</span></a></li>
										<li><a class="icon brands fa-tumblr" href="#"><span class="label">Tumblr</span></a></li>
										<li><a class="icon brands fa-linkedin-in" href="#"><span class="label">LinkedIn</span></a></li>
									</ul>
									<ul class="contact">
										<li>
											<h3>Address</h3>
											<p>
												Untitled Incorporated<br />
												1234 Somewhere Road Suite<br />
												Nashville, TN 00000-0000
											</p>
										</li>
										<li>
											<h3>Mail</h3>
											<p><a href="#">someone@untitled.tld</a></p>
										</li>
										<li>
											<h3>Phone</h3>
											<p>(800) 000-0000</p>
										</li>
									</ul>
								</section>
							</div>
							<div class="col-12">

								<!-- Copyright -->
									<div id="copyright">
										<ul class="links">
											<li>&copy; Untitled. All rights reserved.</li><li>Design: <a href="http://html5up.net">HTML5 UP</a></li>
										</ul>
									</div>

							</div>
						</div>
					</div>
				</section>

		</div>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/jquery.dropotron.min.js"></script>
			<script src="assets/js/browser.min.js"></script>
			<script src="assets/js/breakpoints.min.js"></script>
			<script src="assets/js/util.js"></script>
			<script src="assets/js/main.js"></script>

	</body>
	<?php
		//Fermeture de la communication avec la base MariaDB
		$mysqli->close();
?>
</html>