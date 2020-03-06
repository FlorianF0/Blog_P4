<?php

class User {
	
	public function __construct() {

	}

  public function connexion(){
    global $safeData;

    if($safeData->post['connexion'] !== null){
      if ($safeData->post['identifiant'] == 'jean_forteroche' AND $safeData->post['mdp'] === 'openclassroom'){
        


      }
    } 
  }


}