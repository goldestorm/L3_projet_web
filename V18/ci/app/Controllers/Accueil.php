<?php

namespace App\Controllers;

use App\Models\Db_model;
use CodeIgniter\Exceptions\PageNotFoundException;

class Accueil extends BaseController
{
    public function afficher()
    {
        $model = model(Db_model::class);
        $data['actu'] = $model->get_all_actu();
        return view('templates/haut', $data)
        . view('templates/menu.php')
        . view('affichage_accueil')
        . view('templates/bas');
    }
}
?>