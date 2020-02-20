<?php

require_once "controller/commentaire.php";
require_once "controller/chapitre.php";
require_once "controller/page.php";
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
    if ($uri[1] === "") $this->afficheListeChapitreBack(0);
    if ($uri[1] === "accueil") $this->afficheListeChapitreBack(0);
    if ($uri[1] === "chapitre") $this->afficheChapitreBack($uri[2]);
    if ($uri[1] === "newChapitre") $this->afficheNewChapitre();
    $this->renderPage();    
  }

  private function afficheChapitreBack($slug)
  {

    $monChapitre    = new Chapitre(["slug"=>$slug]);
    $monCommentaire = new Commentaire(["id"=>$monChapitre->id]);

    // die(var_dump( $monCommentaire->afficheCommentaires()));
    $this->titre = $monChapitre->titre;

    $this->html  = new View(
      [
        "{{ titre }}"                     => $monChapitre->titre,
        "{{ partialContenueChapitre }}"   => $monChapitre->afficheContenueChapitreBack(),
        "{{ partialCommentaire }}"        => $monCommentaire->afficheCommentairesBack(),
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
      ],
      "back/partialAccueilBack"
    );
    $this->titre="Accueil";
  }
  
  public function afficheNewChapitre()
  {
    // $monChapitre    = new Chapitre();
    $this->titre = "Ajouter un chapitre";
    $this->html  = file_get_contents("./templates/back/partialCreeChapitre.html");
  }
  
  public function editeChapitre()
  {
    
    $monChapitre    = new Chapitre();
    // $this->titre = "Ajouter un chapitre";
    // $this->html  = file_get_contents("./templates/partialCreeChapitre.html");
  }
}