<?php

namespace App\Controllers;

use App\Models\Db_model;
use CodeIgniter\Exceptions\PageNotFoundException;

class Admin extends BaseController
{
    public function afficher_admin()
    {
        return view('templates/haut_admin')
        . view('templates/bas_admin');
    }
}
?>