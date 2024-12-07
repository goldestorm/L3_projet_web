<?php
namespace App\Models;
use CodeIgniter\Model;

class Db_model extends Model
{
    // Attribut pour la connexion à la base de données
    protected $db;

    public function __construct()
    {
        // Initialisation de la connexion à la base de données
        $this->db = db_connect();
    }

    //----------------PARTIE actualite-------------------

    /**
     * Obtenir une actualité spécifique par son ID.
     *
     * @param int $numero ID de l'actualité.
     * @return object|null Résultat de la requête.
     */
    public function get_actualite($numero)
    {
        $requete = "SELECT * FROM T_actualite_act WHERE id_actualite_act = " . $numero . ";";
        $resultat = $this->db->query($requete);
        return $resultat->getRow();
    }

    /**
     * Obtenir toutes les actualités actives, triées par ordre décroissant.
     *
     * @return array Résultat de la requête sous forme de tableau.
     */
    public function get_all_actu()
    {
        $resultat = $this->db->query("SELECT * FROM T_actualite_act WHERE etat_act = 'A' ORDER BY id_actualite_act DESC LIMIT 5;");
        return $resultat->getResultArray();
    }

    //----------------PARTIE concours-------------------
    /**
     *  concours_exist verify qu un concours exist
     * @param mixed $u nom du concours
     * @return bool 1 ou 0 si il existe ou pas
     */
    public function concours_exist($u)
    {
        $sql = "SELECT * FROM T_concours_con WHERE nom_con = '" . $u . "';";
        $resultat = $this->db->query($sql);
        return $resultat->getNumRows() == 0;
    }
    /**
     * creer un concour avec ces donnée
     * @param mixed $saisie donnée du concours
     * @return mixed
     */
    public function creer_concour($saisie)
    {
        //Récuparation (+ traitement si nécessaire) des données du formulaire
        $saisie['Nom']=addslashes($saisie['Nom']);
        $sql="INSERT INTO T_concours_con VALUES(NULL,'".$saisie['Nom']."',NULL,
        '".$saisie['description']."','".$saisie['edition']."'
        ,'".$saisie['date_debut']."','".$saisie['discipline']."'
        ,".$saisie['nb_j_select'].",'".$saisie['pseudo']."'
        ,".$saisie['nb_j_preselect'].",".$saisie['nb_j_final'].");";
        return $this->db->query($sql);
    }
    /**
     * Obtenir tous les concours.
     *
     * @return array Résultat de la requête sous forme de tableau.
     */
    public function get_all_concour()
    {
        $resultat = $this->db->query("
            SELECT DISTINCT con.id_concours_con, con.nom_con, con.description_con, con.date_debut_con, con.email_cpt, 
                phase_concour(con.id_concours_con) AS phase, 
                (SELECT donner_jury(con.id_concours_con)) AS jury, 
                (SELECT donner_cate(con.id_concours_con)) AS categorie, 
                ADDDATE(con.date_debut_con, con.nb_j_candidature_con) AS date_can,
                ADDDATE(con.date_debut_con, con.nb_j_candidature_con + con.nb_j_preselect_con) AS date_pre,
                ADDDATE(con.date_debut_con, con.nb_j_candidature_con + con.nb_j_preselect_con + con.nb_j_select_con) AS date_selec
            FROM T_concours_con AS con 
            LEFT JOIN T_concours_jury_coj AS coj ON coj.id_concours_con = con.id_concours_con
            LEFT JOIN T_jury_jur AS jur ON jur.email_cpt = coj.email_cpt
            LEFT JOIN T_categorie_concours_coc AS coc ON coc.id_concours_con = con.id_concours_con
            LEFT JOIN T_categorie_cat AS cat ON cat.id_categorie_cat = coc.id_categorie_cat
            ORDER BY (SELECT phase_concour(con.id_concours_con));
        ");
        return $resultat->getResultArray();
    }
    /**
     * renvoie tout les concours auquelle un jury participe
     * @param mixed $email du jury concerné
     * @return mixed donner des concours
     */
    public function get_all_concour_jury($email)
    {
        $resultat = $this->db->query("
            SELECT DISTINCT con.id_concours_con, con.nom_con, con.description_con, con.date_debut_con, con.email_cpt, 
                phase_concour(con.id_concours_con) AS phase, 
                (SELECT donner_jury(con.id_concours_con)) AS jury, 
                (SELECT donner_cate(con.id_concours_con)) AS categorie, 
                ADDDATE(con.date_debut_con, con.nb_j_candidature_con) AS date_can,
                ADDDATE(con.date_debut_con, con.nb_j_candidature_con + con.nb_j_preselect_con) AS date_pre,
                ADDDATE(con.date_debut_con, con.nb_j_candidature_con + con.nb_j_preselect_con + con.nb_j_select_con) AS date_selec
            FROM T_concours_con AS con 
            LEFT JOIN T_concours_jury_coj AS coj ON coj.id_concours_con = con.id_concours_con
            LEFT JOIN T_jury_jur AS jur ON jur.email_cpt = coj.email_cpt
            LEFT JOIN T_categorie_concours_coc AS coc ON coc.id_concours_con = con.id_concours_con
            LEFT JOIN T_categorie_cat AS cat ON cat.id_categorie_cat = coc.id_categorie_cat
            WHERE coj.email_cpt ='".$email."'
            ORDER BY (SELECT phase_concour(con.id_concours_con));
        ");
        return $resultat->getResultArray();
    }
    /**
     * Obtenir les détails d'un concours par son nom.
     *
     * @param string $nom Nom du concours.
     * @return object|null Résultat de la requête.
     */
    public function details($nom)
    {
        $resultat = $this->db->query("
            SELECT DISTINCT *, 
                phase_concour(con.id_concours_con) AS phase, 
                (SELECT donner_jury(con.id_concours_con)) AS jury, 
                (SELECT donner_cate(con.id_concours_con)) AS categorie, 
                ADDDATE(con.date_debut_con, con.nb_j_candidature_con) AS date_can,
                ADDDATE(con.date_debut_con, con.nb_j_candidature_con + con.nb_j_preselect_con) AS date_pre,
                ADDDATE(con.date_debut_con, con.nb_j_candidature_con + con.nb_j_preselect_con + con.nb_j_select_con) AS date_selec
            FROM T_concours_con AS con 
            LEFT JOIN T_concours_jury_coj AS coj ON coj.id_concours_con = con.id_concours_con
            LEFT JOIN T_jury_jur AS jur ON jur.email_cpt = coj.email_cpt
            LEFT JOIN T_categorie_concours_coc AS coc ON coc.id_concours_con = con.id_concours_con
            LEFT JOIN T_categorie_cat AS cat ON cat.id_categorie_cat = coc.id_categorie_cat
            WHERE '" . $nom . "' = con.nom_con;
        ");
        return $resultat->getRow();
    }

    /**
     * Obtenir les candidatures présélectionnées pour un concours donné.
     *
     * @param string $id ID du concours.
     * @return array Résultat de la requête sous forme de tableau.
     */
    public function preselection($id)
    {
        $resultat = $this->db->query("
            SELECT * 
            FROM T_candidature_can 
            JOIN T_concours_con USING(id_concours_con)
            WHERE id_concours_con = '" . $id . "' AND selection_can = 'P';
        ");
        return $resultat->getResultArray();
    }

    /**
     * Obtenir les documents associés aux candidatures présélectionnées pour un concours.
     *
     * @param string $id ID du concours.
     * @return array Résultat de la requête sous forme de tableau.
     */
    public function preselect_doc($id)
    {
        $sql = $this->db->query("
            SELECT * 
            FROM T_candidature_can 
            LEFT JOIN T_document_doc USING(id_candidature_can)  
            WHERE id_concours_con = '" . $id . "' AND selection_can = 'P';
        ");
        return $sql->getResultArray();
    }
    /**
     * sup_concours supression d un concours a partir de son nom
     * @param mixed $saisie nom du concours
     * @return mixed
     */
    public function sup_concours($saisie)
    {
        $sql="DELETE FROM T_concours_con
        WHERE id_concours_con = ".$saisie.";";
        return $this->db->query($sql);
    }

    //------------------PARTIE candidature-------------------

    /**
     * Récupérer une candidature en fonction d'un code et d'un code d'inscription.
     *
     * @param array $saisie Données saisies par l'utilisateur (code et inscription).
     * @return object|null Résultat de la requête.
     */
    public function get_candidature($saisie)
    {
        $code = $saisie['code_can'];
        $code_in = $saisie['inscription'];
        $sql = $this->db->query("
            SELECT *, donner_document(id_candidature_can) AS doc 
            FROM T_candidature_can
            JOIN T_categorie_cat USING(id_categorie_cat)
            JOIN T_concours_con USING(id_concours_con)
            WHERE code_candidature_can = '" . $code . "' AND code_inscription_can = '" . $code_in . "';
        ");
        return $sql->getRow();
    }
       
    /**
     * Récupérer les document d'une candidature en fonction d'un code et d'un code d'inscription.
     *
     * @param array $saisie Données saisies par l'utilisateur (code et inscription).
     * @return object|null Résultat de la requête.
     */
    public function get_candidature_doc($saisie)
    {
        //Récuparation (+ traitement si nécessaire) des données du formulaire
        $code=$saisie['code_can'];
        $code_in=$saisie['inscription'];
        $sql=$this->db->query("SELECT *  FROM `T_candidature_can` 
        LEFT JOIN T_document_doc USING(id_candidature_can)  
        WHERE code_candidature_can = '".$code."' AND code_inscription_can = '".$code_in."'; ");
        return $sql->getResultArray();
    }
    /**
     * Suprimme une candidature en fonction d'un code et d'un code d'inscription.
     *
     * @param array $saisie Données saisies par l'utilisateur (code et inscription).
     * @return object|null Résultat de la requête.
     */
    public function sup_candidature($saisie , $saisie2)
    {
        $sql="CALL DeleteCandidature('".$saisie."','".$saisie2."');";
        return $this->db->query($sql);
    }

    /**
     * Récupérer une candidature en fonction d'un code .
     *
     * @param array $saisie Données saisies par l'utilisateur (code et inscription).
     * @return object|null Résultat de la requête.
     */
    public function get_candidature_simple($code)//avoir candidature avec cade dans barre de recherche
    {
        //Récuparation (+ traitement si nécessaire) des données du formulaire
        $sql=$this->db->query("SELECT *  FROM `T_candidature_can` 
        JOIN T_categorie_cat USING(id_categorie_cat) 
        JOIN T_concours_con USING(id_concours_con) 
        WHERE code_candidature_can = '".$code."'; ");
        return $sql->getRow();
    }
    /**
     * Récupérer les documents d'une candidature en fonction d'un code .
     *
     * @param array $saisie Données saisies par l'utilisateur (code et inscription).
     * @return object|null Résultat de la requête.
     */
    public function get_candidature_doc_simple($code)//avoir document avec cade dans barre de recherche
    {
        //Récuparation (+ traitement si nécessaire) des données du formulaire
        $sql=$this->db->query("SELECT * FROM `T_candidature_can` 
        LEFT JOIN T_document_doc USING(id_candidature_can) 
        WHERE code_candidature_can = '".$code."'; ");
        return $sql->getResultArray();
    }




    //------------------PARTIE compte-------------------
    /**
     * Récupérer le nomnbre de compte .
     *
     * @return object|null Résultat de la requête.
     */
    public function get_nb_compte() // obtenir le nombre de compte
    {
        $resultat = $this->db->query("SELECT COUNT(email_cpt) AS nb_compte  FROM T_compte_cpt; ");
        return $resultat->getRow();
    }
    /**
     * creer un compte jury  .
     *
     * @param array $saisie Données saisies par un administrateur  (email et mot de passe).
     * @return object|null Résultat de la requête.
     */
    public function set_compte_jury($saisie)
    {
        //Récuparation (+ traitement si nécessaire) des données du formulaire
        $login=$saisie['pseudo'];
        $mot_de_passe=$saisie['mdp'];
        $sql="INSERT INTO T_compte_cpt VALUES('".$login."','".$mot_de_passe."','J');";
        return $this->db->query($sql);
    }
    /**
     * creer le detail du compte jury  .
     *
     * @param array $saisie Données saisies par un administrateur (discipline la bio le nom le prenom et l url).
     * @return object|null Résultat de la requête.
     */
    public function set_detail_jury($saisie)
    {
        //Récuparation (+ traitement si nécessaire) des données du formulaire
        $sql="INSERT INTO T_jury_jur VALUES('".$saisie['discipline']."','".$saisie['nom']."',
        '".$saisie['prenom']."','".$saisie['bio']."','".$saisie['url']."','".$saisie['pseudo']."');";
        return $this->db->query($sql);
    }
    /**
     * creer un compte Admin  .
     *
     * @param array $saisie Données saisies par l'utilisateur (email et mot de passe).
     * @return object|null Résultat de la requête.
     */
    public function set_compte_admin($saisie)
    {
        //Récuparation (+ traitement si nécessaire) des données du formulaire
        $login=$saisie['pseudo'];
        $mot_de_passe=$saisie['mdp'];
        $sql="INSERT INTO T_compte_cpt VALUES('".$login."','".$mot_de_passe."','A');";
        return $this->db->query($sql);
    }
    /**
     * creer un compte Admin  .
     *
     * @param array $saisie Données saisies par un administrateur  (email ).
     * @return object|null Résultat de la requête.
     */
    public function set_detail_admin($saisie)
    {
        //Récuparation (+ traitement si nécessaire) des données du formulaire
        $sql="INSERT INTO T_administration_adm VALUES('".$saisie['pseudo']."');";
        return $this->db->query($sql);
    }
    /**
     * Vérifier si un compte existe par son email.
     *
     * @param string $u Email du compte.
     * @return bool True si le compte n'existe pas, False sinon.
     */
    public function compte_exist($u)
    {
        $sql = "SELECT * FROM T_compte_cpt WHERE email_cpt = '" . $u . "';";
        $resultat = $this->db->query($sql);
        return $resultat->getNumRows() == 0;
    }

    /**
     * Authentifier un compte avec son email et mot de passe.
     *
     * @param string $u Email.
     * @param string $p Mot de passe.
     * @return bool True si authentifié, False sinon.
     */
    public function connect_compte($u, $p)
    {
        $sql = "
            SELECT * FROM T_compte_cpt 
            WHERE email_cpt = '" . $u . "' 
            AND mot_de_passe_cpt = SHA2(CONCAT('mdpcacestmoi', '" . $p . "'), 256) AND etat != 'D';
        ";
        $resultat = $this->db->query($sql);
        return $resultat->getNumRows() > 0;
    }

        //recuperation donner du compte
        public function connect_compte_donne($u)
        {
            $sql=$this->db->query("SELECT * FROM T_compte_cpt 
            Left JOIN T_jury_jur USING(email_cpt) 
            WHERE email_cpt='".$u."';");
            return $sql->getRow();
        }



        public function get_all_compte_admin() // obtenir tout comptes
        {
            $resultat = $this->db->query("SELECT * FROM ADMIN; ");
            return $resultat->getResultArray();
        }

        public function get_all_compte_jury() // obtenir tout comptes
        {
            $resultat = $this->db->query("SELECT * FROM JURY; ");
            return $resultat->getResultArray();
        }
        /**
         * met a jour le mot de passe
         * @param mixed $saisie contien le pseudo et le nouveau mot de passe
         * @return mixed
         */
        public function update_mdp($saisie) // obtenir tout comptes
        {
            $resultat = $this->db->query("
             UPDATE T_compte_cpt
            SET mot_de_passe_cpt = SHA2(CONCAT('mdpcacestmoi', '" . $saisie["mdp"]. "'), 256)
            WHERE email_cpt = '".$saisie["pseudo"]."';
            ");
            return $resultat;
        }

    } 
?>