<!-- Connexion à la base MariaDB -->
<?php
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
    //echo ("Connexion BDD réussie !")
    ?>

<?php
    if($_POST['intitule'])
    {
        $intitule = addslashes($_POST['intitule']);
    }

    $sujet="SELECT * FROM t_sujet_suj WHERE sjt_intitule = '". $intitule ."';";
    $compte="SELECT * FROM t_compte_cpt WHERE cpt_pseudo = '".$_SESSION['login']."';";
    if (!$resultat_sujet = $mysqli->query($sujet)) 
    {
        echo("erreur sujet");
        exit();
    }
    else 
    {
        if (!$resultat_compte = $mysqli->query($compte)) 
        {
            echo("erreur compte");
            exit();
        }
        else
        {
            $sujeta=$resultat_sujet->fetch_assoc();
            $comptea=$resultat_compte->fetch_assoc();

            $x = $resultat_sujet->num_rows;
            $y = $resultat_compte->num_rows;
            if($x == 0 && $y == 1)
            {
                $ajout_sujet="INSERT INTO t_sujet_suj (sjt_intitule,sjt_date_ajout,cpt_pseudo) VALUES ('". $intitule ."',CURDATE(),'" . $_SESSION['login'] . "');";
                if (!$resultat_ajout_sujet = $mysqli->query($ajout_sujet)) 
                {
                    echo("erreur compte");
                    echo("<br>");
                    echo($ajout_sujet);
                    echo($resultat_ajout_sujet);
                    exit();
                }else{
                    header("Location:admin_sujets.php");
                }
            }else{
                echo("La requête a echoué il n\'y a soit pas de compte à ce nom soit déjà un sujet à ce nom");
                exit();
            }
        }
    }
?>

<!--Fermeture de la communication avec la base MariaDB-->
<?php
    $mysqli->close();
?>