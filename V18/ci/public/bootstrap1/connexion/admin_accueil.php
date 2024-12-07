<!DOCTYPE html>
<html lang="zxx">
<?php
/* Vérification ci-dessous à faire sur toutes les pages dont l'accès est
autorisé à un utilisateur connecté. */
session_start();


if(!isset($_SESSION['login']) ) //A COMPLETER pour tester aussi le rôle...
{
 //Si la session n'est pas ouverte, redirection vers la page du formulaire
 header("Location:session.php");
}
else{
    if ($_SESSION['role']!='M' && $_SESSION['role']!='G') {
        header("Location:session.php");
    }
}
?>
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Anime Template">
    <meta name="keywords" content="Anime, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Anime | Template</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@300;400;500;600;700;800;900&display=swap"
    rel="stylesheet">

    <!-- Css Styles -->
    <link rel="stylesheet" href="../css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="../css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="../css/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="../css/plyr.css" type="text/css">
    <link rel="stylesheet" href="../css/nice-select.css" type="text/css">
    <link rel="stylesheet" href="../css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="../css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="../css/style.css" type="text/css">
</head>

<body>
    <!-- Page Preloder -->
    <!--div id="preloder">
        <div class="loader"></div>
    </div-->
    <?php
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
    <!-- Header Section Begin -->
    <header class="header">
        <div class="container">
            <div class="row">
                <div class="col-lg-2">
                    <div class="header__logo">
                        <a href="../index.php">
                            <img src="../img/logo.png" alt="">
                        </a>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="header__nav">
                        <nav class="header__menu mobile-menu">
                            <ul>
                                <li><a href="../index.php">Homepage</a></li>
                                <li><a href="../recapitulatif/recapitulatif.php">recapitulatif</a></li>
                                <li ><a href='./admin_sujets.php'>admin_sujets</a></li>;
                                <li class="active"><a href="admin_accueil.php">admin_acceuil</a></li>
                                <li><form action="./deconnexion.php" method="POST"><button type='submit' class='btn btn-danger'>deconnexion</button></form></li>
                            </ul>
                        </nav>
                    </div>
                </div>
        </div>
    </header>
    <?php
/* Code PHP permettant de souhaiter la bienvenue à l’utilisateur connecté et
d’afficher le détail de son profil. */
    echo("<h1 style='color:white;'>Bienvenu</h1>");
    echo("<br>");
    echo("<h3 style='color:white;'>".$_SESSION['login']."</h3>");
    echo("<br>");
    echo("<h3 style='color:white;'>".$_SESSION['role']."</h3>");
    echo("<br>");
    $sltp= "SELECT * FROM t_profil_pfl WHERE cpt_pseudo='".$_SESSION['login']."';";
    if ($res= $mysqli->query($sltp)) {
        $profil = $res->fetch_assoc();
        echo("<h3 style='color:white;'>Votre prenom : ".$profil['pfl_prenom']."</h3>");
        echo("<br>");
        echo("<h3 style='color:white;'>Votre nom : ".$profil['pfl_nom']."</h3>");
        echo("<br>");
    }
    if ('G'==$_SESSION['role']) {
        $query = "SELECT * FROM t_profil_pfl";
            if ($result = $mysqli->query($query)) {/* Détermine le nombre de lignes du jeu de résultats */
                $row_cnt = $result->num_rows;
                if ( $result->num_rows >= 1){
                    echo("<h4 style='color:white;'>");
                    printf("Il y a  %d comptes et proflis.\n", $row_cnt);
                    echo("</h4>");
                    echo("<br>");
                    echo("<table class='table' style='color: white;'><tr><th>Prénom</th><th>nom</th><th>Validité(A=actif / D=desactivé)</th><th>Statut(M=membre / G=gestionnaire)</th><th>email</th><th>changement de validité</th><th>changement de statut</th></tr>");
                    /* Récupère un tableau associatif */
                    while ($profil = $result->fetch_assoc()) {
                        echo("<tr>");
                        echo("<td>" . $profil['pfl_prenom'] . "</td>");
                        echo("<td>" . $profil['pfl_nom'] . "</td>");
                        echo("<td>" . $profil['pfl_validite'] . "</td>");
                        echo("<td>" . $profil['pfl_statut'] . "</td>");
                        echo("<td>" . $profil['cpt_pseudo'] . "</td>");
                        
                        if ($profil['pfl_validite'] == "A") {
                            echo("<td><form action='compte_action.php' method='POST'>");
                            echo("<input type='hidden' name='pseudosel' value='".$profil['cpt_pseudo']."'/>");
                            echo("<input type='hidden' name='style' value='etat'/>");
                            echo("<button type='submit' class='btn btn-primary' name='desactive'>D</button>");
                            echo("</form></td>");
                        }else {
                            echo("<td><form action='compte_action.php' method='POST'>");
                            echo("<input type='hidden' name='pseudosel' value='".$profil['cpt_pseudo']."'/>");
                            echo("<input type='hidden' name='style' value='etat'/>");
                            echo("<button type='submit' class='btn btn-primary' >A</button>");
                            echo("</form></td>");
                        }

                        if ($profil['pfl_statut'] == "G") {
                            echo("<td><form action='compte_action.php' method='POST'>");
                            echo("<input type='hidden' name='pseudosel' value='".$profil['cpt_pseudo']."'/>");
                            echo("<input type='hidden' name='style' value='statut'/>");
                            echo("<button type='submit' class='btn btn-primary'>M</button>");
                            echo("</form></td>");
                        }else {
                            echo("<td><form action='compte_action.php' method='POST'>");
                            echo("<input type='hidden' name='pseudosel' value='".$profil['cpt_pseudo']."'/>");
                            echo("<input type='hidden' name='style' value='statut'/>");
                            echo("<button type='submit' class='btn btn-primary'>G</button>");
                            echo("</form></td>");
                        }
                        
                        
                        echo("</tr>");
                    }
                    echo("</table>");
                    echo("<br/>");}




                else {
                    printf("Pas de données\n");
                }
                                /* Libération des résultats */
            $result->free();
        }
    }

?>
  

    </div>
<!-- Footer Section Begin -->
<footer class="footer">
    <div class="page-up">
        <a href="#" id="scrollToTopButton"><span class="arrow_carrot-up"></span></a>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="footer__logo">
                    <a href="../index.html"><img src="../img/logo.png" alt=""></a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="footer__nav">
                    <ul>
                        <li class="active"><a href="../index.html">Homepage</a></li>
                        <li><a href="#">Contacts</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-3">
                <p><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                  Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | This template is made with <i class="fa fa-heart" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a>
                  <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></p>

              </div>
          </div>
      </div>
  </footer>
  <!-- Footer Section End -->

  <!-- Search model Begin -->
  <div class="search-model">
    <div class="h-100 d-flex align-items-center justify-content-center">
        <div class="search-close-switch"><i class="icon_close"></i></div>
        <form class="search-model-form">
            <input type="text" id="search-input" placeholder="Search here.....">
        </form>
    </div>
</div>
<!-- Search model end -->

<!-- Js Plugins -->
<script src="../js/jquery-3.3.1.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/player.js"></script>
<script src="../js/jquery.nice-select.min.js"></script>
<script src="../js/mixitup.min.js"></script>
<script src="../js/jquery.slicknav.js"></script>
<script src="../js/owl.carousel.min.js"></script>
<script src="../js/main.js"></script>

</body>

</html>