<?php

class User {
	
	public function __construct() {
    if($safeData->post['connexion'] !== null){
      $this->connexion();

	}

  public function connexion(){
    global $safeData;

    if (isset($safeData->post['identifiant'] == 'jean_forteroche') AND isset($safeData->post['mdp'] === 'openclassroom')){
          
        $admin = new Back();

        $admin->accueil();
    } 
  }
}