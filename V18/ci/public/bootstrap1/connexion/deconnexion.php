<?php
    session_start();
    if(isset($_SESSION['pseudo']))
    {
        //Si la session n'est pas ouverte, redirection vers la page du formulaire
        header("Location:../index.php");
    }
    else
    {
        unset($_SESSION['pseudo']);
        session_destroy();
        header("Location:../index.php");
    }
?>