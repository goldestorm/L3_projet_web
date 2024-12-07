<?php

use App\Controllers\Accueil;
use App\Controllers\Admin;
use App\Controllers\Compte;
use App\Controllers\Actualite;
use App\Controllers\Concours;
use App\Controllers\Candidature;


/**
 * @var RouteCollection $routes
 */

    $routes->get('/', [Accueil::class, 'afficher']);
    
    //routes ACTUALITE
    $routes->get('actualite/afficher', [Actualite::class, 'afficher']);
    $routes->get('actualite/afficher/(:num)', [Actualite::class, 'afficher']);

    //orutes CONCOURS
    $routes->get('concours/lister', [Concours::class, 'lister']);
    $routes->get('concours/detail/(:any)', [Concours::class, 'detail']);
    $routes->get('concours/preselection/(:any)', [Concours::class, 'preselection']);
    $routes->get('compte/concours/lister', [Concours::class, 'lister_admin']);
    $routes->get('compte/concours/supprimer/(:num)', [Concours::class, 'supp_concours']);
    $routes->get('compte/concours/lister_jury/(:any)', [Concours::class, 'lister_jury']);
    $routes->get('/concours/detail_jury/(:any)', [Concours::class, 'detail_concours_jury']);
    $routes->get('concours/creer', [Concours::class, 'creer']);
    $routes->post('concours/creer', [Concours::class, 'creer']); 

    //routes CANDIDATURE
    $routes->get('candidature', [Candidature::class, 'afficher']);
    $routes->post('candidature', [Candidature::class, 'afficher']); 
    $routes->get('candidature/suppression/(:any)/(:any)', [Candidature::class, 'supprimer_candidature']);
    $routes->get('candidature/lister', [Candidature::class, 'lister']); 
    //$routes->get('candidature/lister/(:any)', [Candidature::class, 'lister']); 


    //ROUTE COMPTE
    $routes->get('compte/lister', [Compte::class, 'lister']);
    $routes->get('compte/creer', [Compte::class, 'creer']);
    $routes->post('compte/creer', [Compte::class, 'creer']); 
    $routes->get('compte/connecter', [Compte::class, 'connecter']);
    $routes->post('compte/connecter', [Compte::class, 'connecter']);
    $routes->get('compte/deconnecter', [Compte::class, 'deconnecter']);
    $routes->get('compte/afficher_profil', [Compte::class, 'afficher_profil']); 
    $routes->get('compte/creer_admin', [Compte::class, 'creer_admin']);
    $routes->post('compte/creer_admin', [Compte::class, 'creer_admin']); 
    $routes->get('compte/creer_jury', [Compte::class, 'creer_jury']);
    $routes->post('compte/creer_jury', [Compte::class, 'creer_jury']); 
    $routes->get('compte/modification_mdp', [Compte::class, 'modif_mdp']);
    $routes->post('compte/modification_mdp', [Compte::class, 'modif_mdp']); 
?>