<!-- Connexion à la base MariaDB -->
<?php
/* Vérification ci-dessous à faire sur toutes les pages dont l'accès est
autorisé à un utilisateur connecté. */
session_start();

$mysqli = new mysqli('localhost','e22100290sql','7HJ5CgwB','e22100290_db2');
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
?>

<?php
    //1) Attribution du pseudo dont on veux changer la validité à la variable $label
    if($_POST["sujetsel"])
    {
        $numero = $_POST["sujetsel"];
    }

    //2) Préparation de la requête récuperant les informations du profil correspondant à $label
    $sujet="SELECT * FROM t_sujet_suj WHERE sjt_id = '". $numero ."';";

    //3) Execution de la requête qui récupère les informations du profil correspondant
    if (!$resultat_sujet = $mysqli->query($sujet)) 
    {// La requête a echoué
        echo("erreur");
        exit();
    }
    else 
    {// La requête a fonctionnée

        //4) Récuperation des informations récupère par la requête
        $resultat_sujet_affichage=$resultat_sujet->fetch_assoc();

        $supprimer_association="DELETE FROM t_lien_fic_hl WHERE fic_id IN (SELECT fic_id FROM t_fiche_fic WHERE sjt_id = '". $numero ."');";
        $supprimer_sujet="DELETE FROM t_sujet_suj WHERE sjt_id = '". $numero ."';";
        $supprimer_fiche="DELETE FROM t_fiche_fic WHERE sjt_id = '". $numero ."';";

        //7) Execution de la requête qui modifie la validité du profil
        if (!$resultat_supprimer_association = $mysqli->query($supprimer_association)) 
        {// La requête a echoué
            echo($supprimer_association);
            echo("erreur fiche ");
            exit();
        }
        else
        {
            if (!$resultat_supprimer_fiche = $mysqli->query($supprimer_fiche)) 
            {// La requête a echoué
                echo("erreur fiche");
                exit();
            }
            else
            {
                if (!$resultat_supprimer_sujet = $mysqli->query($supprimer_sujet)) 
                {// La requête a echoué
                    echo("erreur sujet");
                    exit();
                }
                else
                // La requête a fonctionnée redirection vers la page ADMINISTRATION
                header("Location:admin_sujets.php");
            }
        }
    }
?>

<!--Fermeture de la communication avec la base MariaDB-->
<?php
    $mysqli->close();
?>