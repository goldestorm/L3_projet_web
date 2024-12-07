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
if ($_POST["pseudo"] && $_POST["mdp"]){
    $id=htmlspecialchars(addslashes($_POST['pseudo']));
    $motdepasse=htmlspecialchars(addslashes($_POST['mdp']));}
/* 1) Requête SQL n° 1) incomplète de recherche du compte utilisateur à partir
des pseudo / mot de passe saisis */
$sql="SELECT cpt_pseudo,cpt_mdp FROM t_compte_cpt WHERE
cpt_pseudo='".$id."' AND cpt_mdp=MD5('".$motdepasse."');";
$resultat = $mysqli->query($sql);
if ($resultat==false) {
// La requête a echoué
    echo "Error: Problème d'accès à la base \n";
exit();
}  
else {
    $sqlt="SELECT * FROM t_profil_pfl WHERE cpt_pseudo='".$id."';";
    if (!$restest = $mysqli->query($sqlt)) {
    // La requête a echoué
        echo "Error: Problème d'accès à la base \n";
    exit();
    }else {
        if ($restest->num_rows == 1) {
           $test=$restest->fetch_assoc();
            if ($test['pfl_validite']=='A') {
                if($resultat->num_rows == 1 ) {
                $_SESSION['login']=$id;
                $sql="SELECT * FROM t_profil_pfl WHERE cpt_pseudo='".$id."';";
                $resultat = $mysqli->query($sql);
                $ligne=$resultat->fetch_assoc();
                $_SESSION['role']=$ligne['pfl_statut'];
                header("Location:admin_accueil.php");
                }
                else{
                // aucune ligne retournée
                // => le compte n'existe pas ou n'est pas valide
                echo "pseudo/mot de passe incorrect(s) ou profil inconnu !";
                echo "<br /><a href=\"./session.php\">Cliquez ici pour réafficher
                le formulaire</a>";
                }}else{
                echo('compte desactivé ');
                echo "<br /><a href=\"./session.php\">Cliquez ici pour réafficher le formulaire</a>";
                exit();
            }   
        }else {
            echo('compte inexistant');
            echo "<br /><a href=\"./session.php\">Cliquez ici pour réafficher le formulaire</a>";
            exit();
        } 
        }
        
    }   
?>


<?php
$mysqli -> close();
?>
</body>
</html>