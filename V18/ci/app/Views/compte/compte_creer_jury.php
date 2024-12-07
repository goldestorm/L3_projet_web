<h2><?php echo $titre; ?></h2>
<?= session()->getFlashdata('error') ?>

<?php
// Création d’un formulaire qui pointe vers l’URL de base + /compte/creer
echo form_open('/compte/creer_jury'); ?>
<?= csrf_field() ?>
<label for="pseudo">Pseudo : </label>
<input type="input" name="pseudo">
<?= validation_show_error('pseudo') ?>
<br>
<label for="mdp">Mot de passe : </label>
<input type="password" name="mdp">
<?= validation_show_error('mdp') ?>
<br/>
<label for="mdpv">Mot de passe Vérification : </label>
<input type="password" name="mdpv">
<?= validation_show_error('mdpv') ?>
<br/>
<label for="nom">nom : </label>
<input type="input" name="nom">
<br/>
<label for="prenom">prenom : </label>
<input type="input" name="prenom">
<br/>
<label for="bio">bio : </label>
<input type="input" name="bio">
<br/>
<label for="url">url : </label>
<input type="input" name="url">
<br/>
<label for="discipline">discipline : </label>
<input type="input" name="discipline">
<br/>
<input type="submit" name="submit" value="Créer un nouveau compte">
</form>