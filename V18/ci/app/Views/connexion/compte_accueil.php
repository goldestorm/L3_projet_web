<h2>Espace d'administration</h2>
<br />
<h2>Session ouverte ! Bienvenue
<?php
$session=session();
echo $session->get('user');
?> 
</h2>