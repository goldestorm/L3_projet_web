<h2><?php echo $titre; ?></h2>
<?= session()->getFlashdata('error') ?>

<?php
// Création d’un formulaire qui pointe vers l’URL de base + /compte/creer
echo form_open('/concours/creer'); ?>
<label for="Nom">Nom : </label>
<input type="input" name="Nom">
<br>
<label for="description">Description: </label>
<input type="input" name="description">
<br/>
<label for="edition">edition : </label>
<input type="input" name="edition">
<br/>
<label for="date_debut">date debut (année-mois-jours) : </label>
<input type="input" name="date_debut">
<br/>
<label for="nb_j_preselect">Nombre jour pre-selection : </label>
<input type="input" name="nb_j_preselect">
<br/>
<label for="nb_j_select">Nombre jour selection : </label>
<input type="input" name="nb_j_select">
<br/>
<label for="nb_j_final">Nombre jour final : </label>
<input type="input" name="nb_j_final">
<br/>
<label for="discipline">discipline : </label>
<input type="input" name="discipline">
<br/>
<label for="pseudo">Pseudo : </label>
<input type="text" name="pseudo" value="<?=$login->email_cpt?>" readonly>
<br/>
<input type="submit" name="submit" value="Créer un nouveau concours">
</form>