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
      <th scope="col">info</th>
      <th scope="col">action</th>
    </tr>
  </thead>
  <tbody>


<?php
if ($concours)
{
        echo("<tr>");
            echo("<td>".$concours->nom_con."</td>");
            echo("<td>".$concours->phase."</td>");
            echo("<td>".$concours->categorie."</td>");
            echo("<td>".$concours->description_con."</td>");
            echo("<td>inscription<br/>".$concours->date_debut_con."<br/>preselection<br/>".$concours->date_can."<br/>selection<br/>".$concours->date_pre."<br/>terminé<br/>".$concours->date_selec."</td>");
            echo("<td>".$concours->jury."</td>");
            echo("<td>".$concours->email_cpt."</td>");
            if($concours->phase == 'inscription'){
              echo("<td><a href='".base_url()."index.php/concours/detail/".$concours->nom_con."'>
              <img style='width: 50px; height: 50px;' src='". base_url()."bootstrap1/images/loupe.png'/>
              </a></td>");
              echo("<td><img style='width: 50px; height: 50px;' src='". base_url()."bootstrap1/images/inscription.png'/></td>");
            }else if($concours->phase == 'preselection'){
              echo("<td><a href='".base_url()."index.php/concours/detail/".$concours->nom_con."'>
              <img style='width: 50px; height: 50px;' src='". base_url()."bootstrap1/images/loupe.png'/>
              </a></td>");
              echo("<td><a href='".base_url()."index.php/concours/detail'>
              <img style='width: 50px; height: 50px;' src='". base_url()."bootstrap1/images/document.png'/>
              </a></td>");
            }else if($concours->phase == 'selection'){
              echo("<td><a href='".base_url()."index.php/concours/detail/".$concours->nom_con."'>
              <img style='width: 50px; height: 50px;' src='". base_url()."bootstrap1/images/loupe.png'/>
              </a></td>");
              echo("<td><img style='width: 50px; height: 50px;' src='". base_url()."bootstrap1/images/document.png'/></td>");
            }else if($concours->phase == 'terminé'){
              echo("<td><a href='".base_url()."index.php/concours/detail/".$concours->nom_con."'>
              <img style='width: 50px; height: 50px;' src='". base_url()."bootstrap1/images/loupe.png'/>
              </a></td>");
              echo("<td><img style='width: 50px; height: 50px;' src='". base_url()."bootstrap1/images/trophe.png'/></td>");
            }else{
              echo("<td><a href='".base_url()."index.php/concours/detail/".$concours->nom_con."'>
              <img style='width: 50px; height: 50px;' src='". base_url()."bootstrap1/images/loupe.png'/>
              </a></td>");
            }
        echo("</tr>"); 
}
else {
    echo ("<h3>pas de concours </h3>");
}
?> 
 </tbody>
</table>