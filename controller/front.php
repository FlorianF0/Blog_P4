<?php

require_once "controller/commentaire.php";
require_once "controller/chapitre.php";
require_once "controller/page.php";
require_once "controller/user.php";
require_once "view/view.php";


class Front extends Page
{

  public $id;
  public $id_chapitre;
  private $nombreParPage = 5;
  private $uri;

  /**
   * [__construct description]
   * @param Array $uri [description]
   */
  public function __construct($uri)
  {

    // $user = new User();

    $this->uri = $uri;

    if ( isset($uri[0]) ) $todo = $uri[0];
    else $todo = "accueil";
    if ($todo === "") $todo = "accueil";
    if (!method_exists($this, $todo)) $todo = "page404";

    $this->$todo();

    // if ($user->name === null) $todo = "login";

    $this->renderPage();
  }


  private function accueil(){
    $this->afficheListeChapitre(0);
  }

  private function chapitre(){
    $this->afficheChapitre($this->uri[1]);
  }

  private function connexion() {
    $this->afficheConnexion();
  }


  private function afficheChapitre($slug)
  {

    $monChapitre = new Chapitre(["slug"=>$slug]);
    
    // redirige vers une page error404 
    if (!isset($monChapitre->titre)){
      $this->page404();
      return;
    }

    $monCommentaire = new Commentaire(["id"=>$monChapitre->id]);
    $this->titre = $monChapitre->titre;

    $this->html  = new View(
      [
        "{{ titre }}"                     => $monChapitre->titre,
        "{{ partialContenueChapitre }}"   => $monChapitre->afficheContenueChapitre(),
        "{{ partialCommentaire }}"        => $monCommentaire->afficheCommentaires(),
        "{{ partialFooter }}"             => $this->footer(),
      ],
      "partialChapitre"
    );
  }

  

  public function afficheListeChapitre($depart)
  {

    
    $mesChapitres = new Chapitre(["start"    =>$depart,
                                  "quantity" => $this->nombreParPage]);

    $this->html  = new View(
      [
        "{{ partialListeChapitre }}"    => $mesChapitres->html,
        "{{ partialFooter }}"           => $this->footer(),

      ],
      "front/partialAccueil"
    );

    $this->titre = "Accueil";    
  }


  // public function afficheConnexion() {
  //     $this->html = file_get_contents("./templates/partialFormConnexion.html");
  // }
}