<?php

require_once "controller/commentaire.php";
require_once "controller/chapitre.php";
require_once "controller/page.php";
require_once "controller/user.php";
require_once "view/view.php";

/**
 * Class Front
 *
 * Permet de générer le contenu lié au front
 */
class Front extends Page
{

  /**
  * @var int
  * @var int
  * @var int
  * @var array
  */

  public $id;
  public $id_chapitre;
  private $nombreParPage = 5;
  private $uri;

  /**
   * Permet d'afficher les pages en fonction de l'uri 
   * @param Array $uri : barre d'adresse
   * @param $todo : fonction associé au front
   */
  public function __construct( $uri )
  {

    $this->uri = $uri;

    if ( isset( $uri[0] ) ) $todo = $uri[0];
    else $todo = "accueil";
    if ($todo === "") $todo = "accueil";
    if ( !method_exists( $this , $todo ) ) $todo = "page404";

    $this->$todo();

    $this->renderPage();
  }


  private function accueil(){
    $this->afficheListeChapitre(0);
  }

  private function chapitre(){
    $this->afficheChapitre( $this->uri[1] );
  }

  /**
   * @param string $slug
   * 
   * @return string $html : contenu de la page 
   */

  private function afficheChapitre( $slug )
  {

    $monChapitre = new Chapitre( ["slug" => $slug] );
    
    // redirige vers une page error404 
    if ( !isset( $monChapitre->titre ) ){
      $this->page404();
      return;
    }

    $monCommentaire = new Commentaire( ["id" => $monChapitre->id] );
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

  /**
   * @param int $depart : nbr auquel on le veut démarer la liste des chapitres
   *
   */

  public function afficheListeChapitre( $depart )
  {

    
    $mesChapitres = new Chapitre( ["start"    => $depart,
                                   "quantity" => $this->nombreParPage] );

    $this->html  = new View(
      [
        "{{ partialListeChapitre }}"    => $mesChapitres->html,
        "{{ partialFooter }}"           => $this->footer(),

      ],
      "front/partialAccueil"
    );

    $this->titre = "Accueil";    
  }
}