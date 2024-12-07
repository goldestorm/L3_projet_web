<?php

namespace App\Controllers;
use App\Models\Db_model;

use CodeIgniter\Exceptions\PageNotFoundException;

class Concours extends BaseController
{
    public function __construct()
        {
        //...
        }
    /**
     * affiche les concours pour les visiteurs coté front office
     * @return string tout les concours
     */
    public function lister()
        {
            $model = model(Db_model::class);
            $data['concours'] = $model->get_all_concour();
            return view('templates/haut', $data)
            . view('templates/menu.php')
            . view('affichage_concours.php')
            . view('templates/bas');
        }
    /**
     * affiche les concours pour les admins coté back office
     * @return string tout les concours
     */
    public function lister_admin()
        {
            $model = model(Db_model::class);
            $data['concours'] = $model->get_all_concour();
            return view('templates/haut_admin', $data)
            . view('concours/concours_admin.php')
            . view('templates/bas_admin');
        }
    /**
     * retourne la vue a afiiche pour les concours qu un jury note
     * @param mixed $email du jury
     * @return string
     */
    public function lister_jury($email)
        {
            $model = model(Db_model::class);
            $data['concours'] = $model->get_all_concour_jury($email);
            return view('templates/haut_admin', $data)
            . view('concours/concours_jury.php')
            . view('templates/bas_admin');
        }

    /**
     * affiche les details s'un concours
     * @param mixed $nom id du concours
     * @return string les views pour le concours
     */
    public function detail($nom)
        {
            $model = model(Db_model::class);
            $data['concours'] = $model->details(addslashes($nom));
            return view('templates/haut', $data)
            . view('templates/menu.php')
            . view('concours/details_concours.php')
            . view('templates/bas');
        }
    /**
     * montre tout les candidats pre selectionnés
     * @param mixed $nom id du concours
     * @return string les view et les donnees pour affiché
     */
    public function preselection($nom)
        {
            $model = model(Db_model::class);
            $data['candidats'] = $model->preselection($nom);
            $data['document'] = $model->preselect_doc($nom);
            return view('templates/haut', $data)
            . view('templates/menu.php')
            . view('concours/preselection.php')
            . view('templates/bas');
        }



        public function creer()
    {
        helper('form');
        $model = model(Db_model::class);
        $session=session();
        $data['login'] = $model->connect_compte_donne($session->get('user'));
        // L’utilisateur a validé le formulaire en cliquant sur le bouton
        if ($this->request->getMethod()=="post")
        {
            // La validation du formulaire a réussi, traitement du formulaire
            //$recuperation = $this->validator->getValidated();
            $recuperation["Nom"]=$this->request->getVar('Nom');
            $recuperation["description"]=$this->request->getVar('description');
            $recuperation["edition"]=$this->request->getVar('edition');
            $recuperation["date_debut"]=$this->request->getVar('date_debut');
            $recuperation["nb_j_preselect"]=$this->request->getVar('nb_j_preselect');
            $recuperation["nb_j_select"]=$this->request->getVar('nb_j_select');
            $recuperation["nb_j_final"]=$this->request->getVar('nb_j_final');
            $recuperation["discipline"]=$this->request->getVar('discipline');
            $recuperation["pseudo"]=$this->request->getVar('pseudo');
            
            if ($model->concours_exist($recuperation['Nom'])==true ) {
                $model->creer_concour($recuperation);
                return view('templates/haut_admin')
                . view('concours/concours_success')
                . view('templates/bas_admin');
            }else {
                $data['titre'] = 'Créer un concours';
                return view('templates/haut_admin',$data)
                . view('concours/concours_creer')
                . view('templates/bas_admin');
            }
        }
        // L’utilisateur veut afficher le formulaire pour créer un compte
        $data['titre'] = 'Créer un concours';
        return view('templates/haut_admin',$data)
        . view('concours/concours_creer')
        . view('templates/bas_admin');
    }
    public function supp_concours($nom)
        {
            $model = model(Db_model::class);
            $model->sup_concours($nom);
            return view('templates/haut_admin')
            . view('candidature/suppression_reussi')
            . view('templates/bas_admin');

        }
        /**
     * affiche les details des candidature
     * @param mixed $nom id du concours
     * @return string les views pour le concours
     */
    public function detail_concours_jury($nom)
        {
            $model = model(Db_model::class);
            $data['candidats'] = $model->preselection($nom);
            $data['document'] = $model->preselect_doc($nom);
            return view('templates/haut_admin', $data)
            . view('concours/preselection.php')
            . view('templates/bas_admin');
        }
}
?>