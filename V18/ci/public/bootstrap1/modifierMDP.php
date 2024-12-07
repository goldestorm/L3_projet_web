<?php
							echo ("Page Web OK");
							// Connexion à la base MariaDB
							$mysqli = new mysqli('localhost','e22100290sql','2YUknMDj','e22100290_db1');
							if ($mysqli->connect_errno) {
							//...
							}
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
							//Fermeture de la communication avec la base MariaDB
							$mysqli->close();
							?>
