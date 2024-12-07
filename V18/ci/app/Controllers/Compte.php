<?php
namespace App\Controllers;
use App\Models\Db_model;
use CodeIgniter\Exceptions\PageNotFoundException;

class Compte extends BaseController
{
    public function __construct()
    {
        helper('form');
        $this->model = model(Db_model::class);

    }
    public function lister()
    {
        $model = model(Db_model::class);
        $data['titre']="Liste de tous les comptes";
        $data['jury'] = $model->get_all_compte_jury();
        $data['admin'] = $model->get_all_compte_admin();
        $data['nombre'] = $model->get_nb_compte();
        return view('templates/haut_admin', $data)
        . view('compte/affichage_comptes')
        . view('templates/bas_admin');
    }

    //connexion
    public function connecter()
    {
        $model = model(Db_model::class);
        // L’utilisateur a validé le formulaire en cliquant sur le bouton
        if ($this->request->getMethod()=="post"){
            if (! $this->validate([
            'pseudo' => 'required',
            'mdp' => 'required'
            ])){ // La validation du formulaire a échoué, retour au formulaire !
                return view('templates/haut', ['titre' => 'Se connecter'])
                . view('templates/menu')
                . view('connexion/compte_connecter')
                . view('templates/bas');
            }
            // La validation du formulaire a réussi, traitement du formulaire
            $username=addslashes($this->request->getVar('pseudo'));
            $password=addslashes($this->request->getVar('mdp'));
            $data['login'] = $model->connect_compte_donne($username);
            if ($model->connect_compte($username,$password)==true) {
                $session=session();
                $session->set('user',$username);
                $session->set('etat', $this->model->connect_compte_donne($username)->etat);
                return view('templates/haut_admin',$data)
                . view('connexion/compte_accueil')
                . view('templates/bas_admin');
            } else{ 
                return view('templates/haut', ['titre' => 'Se connecter'])
                . view('templates/menu')
                . view('connexion/compte_connecter')
                . view('templates/bas');
            }
        }
        // L’utilisateur veut afficher le formulaire pour se connecter
        return view('templates/haut', ['titre' => 'Se connecter'])
        . view('templates/menu')
        . view('connexion/compte_connecter')
        . view('templates/bas');
    }

    //affiche le profile
    public function afficher_profil() {
        $model = model(Db_model::class);
        $session=session();
        if ($session->has('user'))
        {
            $data['le_message']="Affichage des données du profil ici !!!";
            return view('templates/haut_admin',$data)
            . view('connexion/compte_profil')
            . view('templates/bas_admin');
        }
        else
        {
            return view('templates/haut_admin', ['titre' => 'Se connecter'])
            . view('connexion/compte_connecter')
            . view('templates/bas_admin');
        }
    }
    //deco
    public function deconnecter()
    {
        $session=session();
        $session->destroy();
        return view('templates/haut', ['titre' => 'Se connecter'])
        . view('templates/menu')
        . view('connexion/compte_connecter')
        . view('templates/bas');
    }

    //Creation jury
    public function creer_jury()
    {
        helper('form');
        $model = model(Db_model::class);
        $session=session();
        $data['login'] = $model->connect_compte_donne($session->get('user'));
        // L’utilisateur a validé le formulaire en cliquant sur le bouton
        if ($this->request->getMethod()=="post")
        {
            if (! $this->validate([
                'pseudo' => 'required|max_length[255]|min_length[2]',
                'mdp' => 'required|max_length[255]|min_length[8]'
            ],[ // Configuration des messages d’erreurs
                'pseudo' => [
                'required' => 'Veuillez entrer un pseudo pour le compte !',
                ],
                'mdp' => [
                'required' => 'Veuillez entrer un mot de passe !',
                'min_length' => 'Le mot de passe saisi est trop court !',
                ],
             ]
            ))
            {
                // La validation du formulaire a échoué, retour au formulaire !
                $data['titre'] = 'Créer un compte';
                return view('templates/haut_admin',$data)
                . view('compte/compte_creer_jury')
                . view('templates/bas_admin');
            }
            // La validation du formulaire a réussi, traitement du formulaire
            //$recuperation = $this->validator->getValidated();
            $recuperation["pseudo"]=$this->request->getVar('pseudo');
            $recuperation["mdpv"]=$this->request->getVar('mdpv');
            $recuperation["mdp"]=$this->request->getVar('mdp');
            $recuperation["nom"]=$this->request->getVar('nom');
            $recuperation["prenom"]=$this->request->getVar('prenom');
            $recuperation["discipline"]=$this->request->getVar('discipline');
            $recuperation["url"]=$this->request->getVar('url');
            $recuperation["bio"]=$this->request->getVar('bio');

            $recuperation["mdp"]=addslashes($recuperation["mdp"]);
            $recuperation["mdpv"]=addslashes($recuperation["mdpv"]);
            $recuperation["bio"]=addslashes($recuperation["bio"]);
            if ($model->compte_exist($recuperation['pseudo'])==true && $recuperation["mdpv"]==$recuperation["mdp"] ) {
                $model->set_compte_jury($recuperation);
                $model->set_detail_jury($recuperation);
                $data['le_compte']=$recuperation['pseudo'];
                $data['le_message']="Nouveau nombre de comptes : ";
                //Appel de la fonction créée dans le précédent tutoriel :
                $data['le_total']=$model->get_nb_compte(); 
                return view('templates/haut_admin', $data)
                . view('compte/compte_success')
                . view('templates/bas_admin');
            }else {
                $data['titre'] = 'Créer un compte';
                return view('templates/haut_admin',$data)
                . view('compte/compte_creer_jury')
                . view('templates/bas_admin');
            }
        }
        // L’utilisateur veut afficher le formulaire pour créer un compte
        $data['titre'] = 'Créer un compte';
        return view('templates/haut_admin',$data)
        . view('compte/compte_creer_jury')
        . view('templates/bas_admin');
    }
    public function creer_admin()
    {
        helper('form');
        $model = model(Db_model::class);
        $session=session();
        $data['login'] = $model->connect_compte_donne($session->get('user'));
        // L’utilisateur a validé le formulaire en cliquant sur le bouton
        if ($this->request->getMethod()=="post")
        {
            if (! $this->validate([
                'pseudo' => 'required|max_length[255]|min_length[2]',
                'mdp' => 'required|max_length[255]|min_length[8]'
            ],[ // Configuration des messages d’erreurs
                'pseudo' => [
                'required' => 'Veuillez entrer un pseudo pour le compte !',
                ],
                'mdp' => [
                'required' => 'Veuillez entrer un mot de passe !',
                'min_length' => 'Le mot de passe saisi est trop court !',
                ],
             ]
            ))
            {
                // La validation du formulaire a échoué, retour au formulaire !
                $data['titre'] = 'Créer un compte';
                return view('templates/haut_admin',$data)
                . view('compte/compte_creer_admin')
                . view('templates/bas_admin');
            }
            // La validation du formulaire a réussi, traitement du formulaire
            //$recuperation = $this->validator->getValidated();

            $recuperation["pseudo"]=$this->request->getVar('pseudo');
            $recuperation["mdpv"]=$this->request->getVar('mdpv');
            $recuperation["mdp"]=$this->request->getVar('mdp');
            
            $recuperation["mdp"]=addslashes($recuperation["mdp"]);
            $recuperation["mdpv"]=addslashes($recuperation["mdpv"]);
            if ($model->compte_exist($recuperation['pseudo'])==true && $recuperation["mdpv"]==$recuperation["mdp"] ) {
                $model->set_compte_admin($recuperation);
                $model->set_detail_admin($recuperation);
                $data['le_compte']=$recuperation['pseudo'];
                $data['le_message']="Nouveau nombre de comptes : ";
                //Appel de la fonction créée dans le précédent tutoriel :
                $data['le_total']=$model->get_nb_compte(); 
                return view('templates/haut_admin', $data)
                . view('compte/compte_success')
                . view('templates/bas_admin');
            }else {
                $data['titre'] = 'Créer un compte';
                return view('templates/haut_admin',$data)
                . view('compte/compte_creer_admin')
                . view('templates/bas_admin');
            }
        }
        // L’utilisateur veut afficher le formulaire pour créer un compte
        $data['titre'] = 'Créer un compte';
        return view('templates/haut_admin',$data)
        . view('compte/compte_creer_admin')
        . view('templates/bas_admin');
    }
    public function modif_mdp()
    {
        helper('form');
        $model = model(Db_model::class);
        $session=session();
        $data['login'] = $model->connect_compte_donne($session->get('user'));
        // L’utilisateur a validé le formulaire en cliquant sur le bouton
        if ($this->request->getMethod()=="post")
        {
            if (! $this->validate([
                'pseudo' => 'required|max_length[255]|min_length[2]',
                'mdp' => 'required|max_length[255]|min_length[8]'
            ],[ // Configuration des messages d’erreurs
                'pseudo' => [
                'required' => 'Veuillez entrer un pseudo pour le compte !',
                ],
                'mdp' => [
                'required' => 'Veuillez entrer un mot de passe !',
                'min_length' => 'Le mot de passe saisi est trop court !',
                ],
             ]
            ))
            {
                // La validation du formulaire a échoué, retour au formulaire !
                $data['titre'] = 'nouveau mot de passe';
                return view('templates/haut_admin',$data)
                . view('compte/modification_mdp')
                . view('templates/bas_admin');
            }
            $recuperation["pseudo"]=$this->request->getVar('pseudo');
            $recuperation["mdpv"]=$this->request->getVar('mdpv');
            $recuperation["mdp"]=$this->request->getVar('mdp');
            
            $recuperation["mdp"]=addslashes($recuperation["mdp"]);
            $recuperation["mdpv"]=addslashes($recuperation["mdpv"]);
            if ($recuperation["mdpv"]==$recuperation["mdp"] ) {
                $model->update_mdp($recuperation);
                $data['le_message']="Nouveau nombre de comptes : ";
                return view('templates/haut_admin', $data)
                . view('compte/modif_success')
                . view('templates/bas_admin');
            }else {
                $data['titre'] = 'nouveau mot de passe';
                return view('templates/haut_admin',$data)
                . view('compte/modification_mdp')
                . view('templates/bas_admin');
            }
        }
        // L’utilisateur veut afficher le formulaire modifier un mdp
        $data['titre'] = 'nouveau mot de passe';
        return view('templates/haut_admin',$data)
        . view('compte/modification_mdp')
        . view('templates/bas_admin');
    }
    
}
?>