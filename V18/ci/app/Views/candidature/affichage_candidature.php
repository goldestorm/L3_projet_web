<h2><?php echo $titre; ?></h2>
<?= session()->getFlashdata('error') ?>
<?php
// Création d’un formulaire qui pointe vers l’URL de base + /compte/afficher
echo form_open('/candidature'); ?>
<?= csrf_field() ?>
<label for="inscription">code inscription : </label>
<input type="input" name="inscription">
<label for="code_can">code candidature : </label>
<input type="inpute" name="code_can">
<br/>
<br/>
<input type="submit" name="submit" value="voir candidature">
</form>