<h1>Les candidats pre-selectionnés</h1>
<div class="card-group">

<?php
if (! empty($candidats) && is_array($candidats))
{
    foreach ($candidats as $can)
    {
        echo("<div class='card'>");
        echo("<div class='card-body'>");
        echo("<h5 class='card-title'>".$can["prenom_can"]." ".$can["nom_can"]."</h5>");
        echo("<p class='card-text'>".$can["presentation_can"]."</p>");
        echo("<h5 class='card-title'>Documents</h5>");
        if (! empty($document) && is_array($document))
            {
                foreach ($document as $doc)
                {
                    if ($doc["id_candidature_can"] == $can["id_candidature_can"]) {
                        if ($doc["nom_doc"] == "img") {
                            echo $doc["nom_doc"] .":<br/>";
                            echo("<a href='". base_url());
                            echo("/ressources");
                            echo("/".$doc["description_doc"]."'/>".$doc["description_doc"]."</a>");
                            echo "<br/>";
                          }else {
                            echo $doc["nom_doc"] .":<br/>";
                            echo $doc["description_doc"]."<br/>";
                          }
                    }
                }
            }else {
                echo("pas de document");
            }
        echo("</div>");
        echo("</div>");
    }
}
else {
    echo ("<h3>Pas de condidature</h3>");
}
?> 
</div>