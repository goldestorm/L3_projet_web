<h2><?php echo $titre; ?></h2>
<?= session()->getFlashdata('error') ?>

<?php
// Création d’un formulaire qui pointe vers l’URL de base + /compte/creer
echo form_open('/compte/creer_admin'); ?>
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
<input type="submit" name="submit" value="Créer un nouveau compte">
</form>