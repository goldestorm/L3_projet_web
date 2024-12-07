<?php
namespace App\Controllers;
use App\Models\Db_model;
use CodeIgniter\Exceptions\PageNotFoundException;

class Candidature extends BaseController
{
    public function __construct()
    {
        //...
    }
    //liste la dandidature avec le code dans l url
    public function afficher()
    {
        helper('form');
        $model = model(Db_model::class);

        if ($this->request->getMethod() == "post") {
            if (!$this->validate([
                'inscription' => 'required|max_length[255]|min_length[2]',
                'code_can' => 'required|max_length[255]|min_length[8]'
            ])) {
                return view('templates/haut', ['titre' => 'Voir Ma candidature'])
                    . view('templates/menu.php')
                    . view('candidature/affichage_candidature')
                    . view('templates/bas');
            }

            $recuperation = $this->validator->getValidated();
            $data['candidature']= $model->get_candidature($recuperation);
            $data['document'] = $model->get_candidature_doc($recuperation);
            // Si aucune candidature n'est trouvée, redirigez l'utilisateur
            if (!$data) {
                return redirect()->to('/candidature');  // ou URL complète si nécessaire
            }

            return view('templates/haut', $data)
                . view('templates/menu.php')
                . view('candidature/candidature_success', $data)
                . view('templates/bas');
        }

        return view('templates/haut', ['titre' => 'Voir Ma candidature'])
            . view('templates/menu.php')
            . view('candidature/affichage_candidature')
            . view('templates/bas');
    }
    //liste la dandidature avec le couple code 
    public function lister($code = 0)
        {
            $model = model(Db_model::class);
            if ($code == 0)
            {
                return redirect()->to('/');
            }
            else{
                $data['candidature'] = $model->get_candidature_simple($code);
                $data['document'] = $model->get_candidature_doc_simple($code);
                return view('templates/haut', $data)
                . view('templates/menu.php')
                . view('candidature/candidature_success', $data)
                . view('templates/bas');
            }
        }
        public function supprimer_candidature($code_can , $code_ins)
        {
            $model = model(Db_model::class);
            $model->sup_candidature($code_can , $code_ins);
            return view('templates/haut')
            . view('templates/menu.php')
            . view('candidature/suppression_reussi')
            . view('templates/bas');

        }
}
?>
