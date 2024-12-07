<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>
<body>
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
    $pseudo=$_POST['pseudosel'];
    
    $sql="SELECT * FROM t_profil_pfl WHERE cpt_pseudo='".$pseudo."';";
    echo("<br>");
    echo("<br>");
    if (!$result = $mysqli->query($sql)) {
        echo ("ERROR probleme \n");
        echo ("Query ".$sql."\n");
        echo ("errno".$mysqli->errno."\n");
        echo ("error".$mysqli->error."\n");
        exit();
    }else{
        $ligne =$result -> fetch_assoc();
        if ($_POST['style']=='etat') {
            if ($ligne['pfl_validite']=='A') {
                $sql2 = "UPDATE t_profil_pfl SET pfl_validite = 'D' WHERE cpt_pseudo='".$pseudo."';";
            }else {
                $sql2 = "UPDATE t_profil_pfl SET pfl_validite = 'A' WHERE cpt_pseudo='".$pseudo."';";
            }
        }else if ($_POST['style']=='statut') {
            if ($ligne['pfl_statut']=='G') {
                $sql2 = "UPDATE t_profil_pfl SET pfl_statut = 'M' WHERE cpt_pseudo='".$pseudo."';";
            }else {
                $sql2 = "UPDATE t_profil_pfl SET pfl_statut = 'G' WHERE cpt_pseudo='".$pseudo."';";
            }
        }
        if (!$result2 = $mysqli->query($sql2)) {
            echo ("ERROR probleme \n");
            echo ("Query ".$sql2."\n");
            echo ("errno".$mysqli->errno."\n");
            echo ("error".$mysqli->error."\n");
            exit();
        }else {
            header("Location:admin_accueil.php");
        }
    }
    
    ?>


<?php
$mysqli -> close();
?>
</body>
</html>