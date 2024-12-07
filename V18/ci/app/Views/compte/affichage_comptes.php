<h2><?php echo $titre; ?></h2>
<div class="btn-group" role="group" aria-label="Button group with nested dropdown">
  <div class="btn-group" role="group">
    <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
      Créer
    </button>
    <ul class="dropdown-menu">
      <li><a class="dropdown-item" href="<?php echo base_url();?>index.php/compte/creer_admin">Administrateur</a></li>
      <li><a class="dropdown-item" href="<?php echo base_url();?>index.php/compte/creer_jury">Jury</a></li>
    </ul>
  </div>
</div>

</br>
<?php
    echo "nombre de comptes : ";
    echo $nombre->nb_compte;
    ?>
<h2>ADMIN</h2>
<table class="table">
  <thead>
    <tr>
      <th scope="col">email</th>
      <th scope="col">etat (A=administrateur / D=desactivé)</th>
    </tr>
  </thead>
  <tbody>
<?php
    if (! empty($admin) && is_array($admin))
    {
        foreach ($admin as $ad)
        {
            echo("<tr>");
            echo("<td>".$ad["email_cpt"]."</td>");
            echo("<td>".$ad["etat"]."</td>");      
            echo("</tr>");     
        }
    }
    else {
    }
    ?>
 </tbody>
</table>

<h2>JURY</h2>
<table class="table">
  <thead>
    <tr>
        <th scope="col">email</th>
        <th scope="col">premon</th>
        <th scope="col">nom</th>
        <th scope="col">etat (J=jury / D=desactivé)</th>
        <th scope="col">discipline</th>
        <th scope="col">bio</th>
    </tr>
  </thead>
  <tbody>
<?php
    if (! empty($jury) && is_array($jury))
    {
        foreach ($jury as $ju)
        {
            echo("<tr>");
            echo("<td>".$ju["email_cpt"]."</td>");
            echo("<td>".$ju["prenom_jur"]."</td>");
            echo("<td>".$ju["nom_jur"]."</td>");
            echo("<td>".$ju["etat"]."</td>");     
            echo("<td>".$ju["discipline_jur"]."</td>");
            echo("<td>".$ju["url_jur"]."</td>"); 
            echo("</tr>"); 
        }
    }
    else {
    }
?>
 </tbody>
</table>