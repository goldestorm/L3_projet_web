<?php
    echo ("Page Web OK");
    // Connexion à la base MariaDB
    $mysqli = new mysqli('localhost','e22100290sql','2YUknMDj','e22100290_db1');
    if ($mysqli->connect_errno) {
    //...
    }
    //$uploaddir = '/home/2024DIFAL3/e22100290/public_html/gabarit/documents/';
    $uploaddir = __DIR__. '/documents/';
    $uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
    echo $uploadfile;
    echo '<pre>';
    if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
        echo "Le fichier est valide, et a été téléchargé
        avec succès. Voici plus d'informations :\n";
    } else {
        echo "Le fichier n’a pas été téléchargé. Il y a eu un problème !\n";
        echo "Il est peut etre trop lourd\n";
    }
    echo 'Voici quelques informations sur le téléversement :';
    print_r($_FILES);
    $requete = "INSERT INTO `T_document_doc` (`id_document_doc`, `nom_doc`, `description_doc`, `id_candidature_can`) VALUES
    (NULL, 'img', '".$_FILES['userfile']['name']. "', 1);";
    echo("<br>");
    echo($_FILES['userfile']['name']);
    echo("<br>");
    echo($uploadfile);
    echo("<br>");
    $resultat=$mysqli->query($requete);
    /*Modification du mot de passe du profil de login tuxie*/
    echo($requete);
    if (!$resultat)
    {
        printf("erreur \n ");
    }
    else
    {
    // La requête a réussi...
        printf("requête reussi \n");
    }
?>