<h2>Espace d'administration</h2>
<?php
    $session=session();
    echo $le_message;
    echo ("</br>");
    echo $session->get('user');
    if (isset($login)&& !empty($login->email_cpt)){
        echo("<h2>votre email:</h2>");
        echo $login->email_cpt;
        if($login->etat == "J"){
            echo("</br><h2>prenom:</h2>");
            echo $login->prenom_jur;
            echo("</br><h2>nom:</h2>");
            echo $login->nom_jur;
            echo("</br><h2>etat:Jury</h2>");
            echo("</br><h2>Discipline:</h2>");
            echo $login->discipline_jur;
            echo("</br><h2>bio:</h2></br>");
            echo $login->bio_jur;
        }else {
            echo("</br><h2>etat:Administrateur</h2>");
        }
    }
    else {
        echo ("</br>Probleme de recupération !");
    }
?>
