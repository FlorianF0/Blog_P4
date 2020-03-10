<?php

require_once "controller/commentaire.php";
require_once "controller/chapitre.php";
require_once "controller/page.php";
require_once "controller/user.php";
require_once "view/view.php";


class Back extends Page
{
	public $id;
	public $id_chapitre;
	private $nombreParPage = 5;

  /**
   * [__construct description]
   * @param Array $uri [description]
   */
  public function __construct($uri)
  {
    $this->uri = $uri;

    if ( isset($uri[1]) ) $todo = $uri[1];
    else $todo = "accueil";
    if ($todo === "") $todo = "accueil";

    //on vérifie l'authentification
    $user = new User();
    if ($user->pseudo === null) $todo = "login";

    if (!method_exists($this, $todo)) $todo = "page404";

    $this->$todo();

    $this->renderPage();    
  }

  private function accueil(){
    $this->afficheListeChapitreBack(0);
  }

  private function chapitre(){
    $this->afficheChapitreBack($this->uri[2]);
  }

  private function newChapitre() {
    $this->afficheNewChapitre();
  }

  private function editChapitre() {
    $this->editChapitreBack($this->uri[2]);
  }

  private function afficheChapitreBack($slug)
  {
    // on regarde si le bouton ajout/supprimer est activé et on redirige vers une page (message où l'on indique que l'action à bien était faites)
    $monChapitre    = new Chapitre(["slug"=>$slug]);
      
    // redirige vers une page error404
    if (!isset($monChapitre->titre)){
      $this->page404();
      return;
    }


    global $safeData;    
    if($safeData->post !== null){
      if ($safeData->post["Supprimer_chapitre"]) {
      $this->html = $monChapitre->html;
      return;
      }
    }

    $monCommentaire = new Commentaire(["id"=>$monChapitre->id]);
    $this->titre = $monChapitre->titre;

    $this->html  = new View(
      [
        "{{ titre }}"                     => $monChapitre->titre,
        "{{ partialContenueChapitre }}"   => $monChapitre->afficheContenueChapitreBack(),
        "{{ partialCommentaire }}"        => $monCommentaire->afficheCommentairesBack(),
        "{{ partialFooter }}"             => $this->footer(),

        // "{{ partialFormConnexion }}" => $monChapitre->afficheFormConnexion()
      ],
      "partialChapitre"
    );

  }

  public function afficheListeChapitreBack($depart)
  {

    
    $mesChapitres = new Chapitre(["start"=>$depart, "quantity"=> $this->nombreParPage]);

    $this->html  = new View(
      [
        "{{ partialListeChapitre }}"    => $mesChapitres->html,
        "{{ partialFooter }}"             => $this->footer(),

      ],
      "back/partialAccueilBack"
    );
    $this->titre="Accueil";
  }
  
  public function afficheNewChapitre()
  {
    $monNouveauChapitre = new Chapitre("");

    global $safeData;

    if ($safeData->post["Ajouter_chapitre"]) {
        $this->html = $monNouveauChapitre->html;
        return;
    }

    $this->titre = "Ajouter un chapitre";
    $this->html  = file_get_contents("./templates/back/partialCreeChapitre.html");
  }
  
  public function editChapitreBack($slug)
  {
    
    $monChapitre = new Chapitre(["slug"=>$slug]);

    global $safeData;

    if ($safeData->post["Editer_chapitre"]) {
        $this->html = $monChapitre->html;
        return;
    }

    $this->titre = $monChapitre->titre;

    $this->html  = new View(
      [
        "{{ titre }}"                => $monChapitre->titre,
        "{{ partialEditChapitre }}"  => $monChapitre->afficheEditChapitre(),
      ],
      "back/partialMainEditChapitre"
    );
  }

  private function login(){
    $this->html = file_get_contents("./templates/partialFormConnexion.html");
    $this->titre = "merci de vous identifier";
  }
}