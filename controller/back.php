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
    global $safeData;

    if ($uri[1] === "") $this->afficheListeChapitreBack(0);
    if ($uri[1] === "accueil") $this->afficheListeChapitreBack(0);
    if ($uri[1] === "chapitre") $this->afficheChapitreBack($uri[2]);
    if ($uri[1] === "newChapitre") $this->afficheNewChapitre();
    if ($uri[1] === "editChapitre") $this->editChapitre($uri[2]);
    $this->renderPage();    
  }

  private function afficheChapitreBack($slug)
  {
    // on regarde si le bouton ajout/supprimer est activé et on redirige vers une page (message où l'on indique que l'action à bien était faites)
    $monChapitre    = new Chapitre(["slug"=>$slug]);
    
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
  
  public function editChapitre($slug)
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
}