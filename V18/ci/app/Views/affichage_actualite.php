<h1><?php echo $titre;?></h1><br />
<?php
if (isset($news)){
echo $news->id_actualite_act;
echo(" -- ");
echo $news->titre_actualite_act;
}
else {
echo ("Pas d'actualité !");
}
?>