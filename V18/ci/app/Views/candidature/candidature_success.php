<?php
  if ($candidature)
  {
    echo"<h1>TROUVE</h1>";
  }else {
    echo"<h1>Pas de candidature ou code erroné</h1>";
  }

?>
					
<table class="table">
  <thead>
    <tr>
      <th scope="col">nom</th>
      <th scope="col">prenom</th>
      <th scope="col">presentation</th>
      <th scope="col">concours</th>
      <th scope="col">categorie</th>
      <th scope="col">selection</th>
    </tr>
  </thead>
  <tbody>
    
<?php

if ($candidature)
{

    
    echo("<tr>");
        echo("<td>".$candidature->nom_can."</td>");
        echo("<td>".$candidature->prenom_can."</td>");
        echo("<td>".$candidature->presentation_can."</td>");
        echo("<td>".$candidature->nom_con."</td>");
        echo("<td>".$candidature->nom_cat."</td>");
        echo("<td>".$candidature->selection_can."</td>");
        echo("</tr>"); 
}
echo"</tbody>";
echo"</table>";
echo"<h1>Documents</h1>";
if (! empty($document) && is_array($document))
{
    foreach ($document as $can)
    {
      if (!empty($can["nom_doc"])) {
        if ($can["nom_doc"] == "img") {
          echo $can["nom_doc"] .":<br/>";
          echo("<img style='width: 250px; height: 250px;' src='". base_url());
          echo("/ressources");
          echo("/".$can["description_doc"]."'/>");
          echo "<br/>";
        }else {
          echo $can["nom_doc"] .":<br/>";
          echo $can["description_doc"]."<br/>";
        }
        echo("");
      }else {
        echo"<h2>Pas de Document</h2>";
      }
    }
    echo("<button><a href='");
      echo base_url();
      echo("index.php/candidature/suppression/".$candidature->code_candidature_can."/".$candidature->code_inscription_can."' style='color:white;'>Supprimer</a></button>");
}
else {
  echo"<h2>Pas de Document</h2>";
}

?> 



