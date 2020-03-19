<?php

require_once "controller/commentaire.php";
require_once "controller/chapitre.php";
require_once "controller/page.php";
require_once "controller/user.php";
require_once "view/view.php";


/**
 * Class Back
 *
 * Permet de générer le contenu lié au back
 */
class Back extends Page
{ 

	public  $id;
	public  $id_chapitre;
	private $nombreParPage = 5;

  /**
   * Permet d'afficher les pages en fonction de l'uri
   * @param Array $uri : barre d'adresse 
   * @return Back 
   */
  public function __construct( $uri )
  {
    $this->uri = $uri;

    /**
   * @var $todo : fonction associé au front
     */

    if ( isset( $uri[1] ) ) $todo = $uri[1];
    else $todo = "accueil";
    if ( $todo === "" ) $todo = "accueil";

    //on vérifie l'authentification
    // $_SESSION["pseudo"] = null;

    $user = new User();
    // $_SESSION["pseudo"] = $user->pseudo;
    // die(var_dump($_SESSION["pseudo"]));
    if ( $user->pseudo === null ) $todo = "login";

    if ( !method_exists( $this , $todo ) ) $todo = "page404";

    $this->$todo();

    $this->renderPage();    
  }

  /**
   *
   * @return void
   */
  private function accueil(){
    $this->afficheListeChapitreBack(0);
  }

  /**
   *
   * @return void
   */
  private function chapitre(){
    $this->afficheChapitreBack( $this->uri[2] );
  }

  /**
   *
   * @return void
   */
  private function newChapitre() {
    $this->afficheNewChapitre();
  }

  /**
   *
   * @return void
   */
  private function editChapitre() {
    $this->editChapitreBack( $this->uri[2] );
  }
  /**
   *
   * @return string $html
   */
  private function login(){
    $this->html = file_get_contents( "./templates/back/partialFormConnexion.html" );
    $this->titre = "Merci de vous identifier";
  }

 /**
   * @param string $slug
   * @return string $html : contenu de la page 
   */
  private function afficheChapitreBack( $slug )
  {
    // on regarde si le bouton ajout/supprimer est activé et on redirige vers une page (message où l'on indique que l'action à bien était faites)
    $monChapitre  = new Chapitre( [ "slug" => $slug ] );
      
    // redirige vers une page error404
    if ( !isset( $monChapitre->titre ) ){
      $this->page404();
      return;
    }


    global $safeData;    
    if ( $safeData->post !== null ){
      if ( $safeData->post["Supprimer_chapitre"] ) {
      $this->html = $monChapitre->html;
      return;
      }
    }

    $monCommentaire = new Commentaire( [ "id" => $monChapitre->id ] );
    $this->titre = $monChapitre->titre;

    $this->html  = new View(
      [
        "{{ titre }}"                     => $monChapitre->titre,
        "{{ partialContenueChapitre }}"   => $monChapitre->afficheContenueChapitreBack(),
        "{{ partialCommentaire }}"        => $monCommentaire->afficheCommentairesBack(),
        "{{ partialFooter }}"             => $this->footer(),
      ],
      "partialChapitre"
    );

  }

  /**
   * @param int $depart : nbr auquel on le veut démarer la liste des chapitres
   *
   * @return string $html
   */
  public function afficheListeChapitreBack( $depart )
  {

    
    $mesChapitres = new Chapitre( [ "start" => $depart, "quantity"=> $this->nombreParPage ] );

    $this->html  = new View(
      [
        "{{ partialListeChapitre }}"      => $mesChapitres->html,
        "{{ partialFooter }}"             => $this->footer(),

      ],
      "back/partialAccueilBack"
    );
    $this->titre="Accueil";
  }
  /**
   * @return string $html
   *
   */
  public function afficheNewChapitre()
  {
    $monNouveauChapitre = new Chapitre("");

    global $safeData;

    if ( $safeData->post["Ajouter_chapitre"] ) {
        $this->html = $monNouveauChapitre->html;
        return;
    }

    $this->titre = "Ajouter un chapitre";
    $this->html  = file_get_contents( "./templates/back/partialCreeChapitre.html" );
  }

    /**
   * @param string $slug
   *
   * @param string $html 
   *
   */
  public function editChapitreBack( $slug )
  {
    
    $monChapitre = new Chapitre( [ "slug"=>$slug ] );

    global $safeData;

    if ( $safeData->post["Editer_chapitre"] ) {
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