<table class="table">
  <thead>
    <tr>
      <th scope="col">nom</th>
      <th scope="col">phase</th>
      <th scope="col">categorie</th>
      <th scope="col">description</th>
      <th scope="col">date</th>
      <th scope="col">jury</th>
      <th scope="col">créateur</th>
      <th scope="col">suppression</th>
    </tr>
  </thead>
  <tbody>
  <a href="<?php echo base_url();?>index.php/concours/creer">
    <button>Creer</button>
  </a>

<?php
  

if (! empty($concours) && is_array($concours))
{
    foreach ($concours as $conc)
    {
        
        echo("<tr>");
            echo("<td>".$conc["nom_con"]."</td>");
            echo("<td>".$conc["phase"]."</td>");
            echo("<td>".$conc["categorie"]."</td>");
            echo("<td>".$conc["description_con"]."</td>");
            echo("<td>inscription<br/>".$conc["date_debut_con"]."<br/>preselection<br/>".$conc["date_can"].
            "<br/>selection<br/>".$conc["date_pre"]."<br/>terminé<br/>".$conc["date_selec"]."</td>");
            echo("<td>".$conc["jury"]."</td>");
            echo("<td>".$conc["email_cpt"]."</td>");
            echo("<td><button><a href='");
            echo base_url();
            echo("index.php/compte/concours/supprimer/".$conc["id_concours_con"]."' style='color:white;'>Supprimer</a></button></td>");
        echo("</tr>"); 
    }
}
else {
    echo ("<h3>Erreur</h3>");
}
?> 
 </tbody>
</table>