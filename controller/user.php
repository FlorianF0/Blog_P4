<?php

require_once "model/userModel.php";

class User {

  public $pseudo;
  public $email;
  public $date;
	
	public function __construct() {
    global $safeData;

    if ($safeData->post['connexion'] !== null){
      $this->connexion($safeData->post['identifiant'],$safeData->post['mdp']);
    }
    else {
      $this->getSessionData();
    }

	}

  private function connexion($identifiant, $mdp){
    global $safeData;

    $mdp = $safeData->encode($mdp);
    
    $model = new UserModel([
      "check" => compact("identifiant", "mdp")
    ]);
    if ($model->donneesRead !== false) {
      $this->pseudo = $model->donneesRead["pseudo"];
      $this->email  = $model->donneesRead["email"];
      $this->saveSession();
    }
  }

  private function getSessionData(){
    //on regarde si on a des données en session
    if (empty($_SESSION["pseudo"] AND $_SESSION["email"]));

    //on regarde si timestamp + durée session > maintenant
    if ($this->date + 2*3600 > date("F j, Y, g:i a"));
  }

  private function saveSession(){
    //on enregistre les données en session ($this->pseudo,  $this->email)
    $_SESSION["pseudo"] = $this->pseudo;
    $_SESSION["email"]  = $this->email;

    //on ajoute timestamp de maintenant
    $this->date = date_create();
  }
}