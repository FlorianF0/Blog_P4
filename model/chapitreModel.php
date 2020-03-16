<?php

require_once "model/model.php";

/**
 * 
 */
class ChapitreModel extends Model
{
 /**
   * @param string|int $argument 
   * 
   */
  function __construct( $argument )
  {
    parent::__construct();
    global $safeData;

    // on récupère le chapitre grâce au slug
    if ( isset( $argument["slug"] ) )   $this->getDataFromSlug( $argument["slug"] );

    // on récupère le chapitre grâce a l'id
    if ( isset( $argument["id"] ) )     $this->getDataFromId( $argument["id"] );

    // affiche la liste des articles (argument: start->de quel id on commence, quandtiy->le nbr de chapitre que l'onveux sur la page)
    if ( isset($argument["start"] ) )  $this->getList( $argument["start"] , $argument["quantity"] );


    // quand un input est éctivité, on appel la fonction associé
    if ( $safeData->post['Supprimer_chapitre'] !== null ) {
      $this->deleteChapitre( $safeData->post['idChapitre'] );
    }
    
    if ( $safeData->post['Editer_chapitre'] !== null ) {
      $this->editChapitre( $safeData->post['titre'] , $safeData->post['resume'] , $safeData->post['slug'] , $safeData->post['contenu'] );
    }

    if ( $safeData->post['Ajouter_chapitre'] !== null ) {
      if ( isset($safeData->post['titre'] ) AND isset( $safeData->post['resume'] ) AND isset( $safeData->post['slug'] ) AND isset( $safeData->post['contenu'] ) ){
        $this->createChapitre( $safeData->post['titre'] , $safeData->post['resume'] , $safeData->post['slug'] , $safeData->post['contenu'] );
      }
    }
  }

 /**
   * @param int $id 
   * 
   */
  private function getDataFromId( $id ){
    $sql = "SELECT id, auteur, titre, contenu, resume slug, DATE_FORMAT(dateAjout, '%d %M %Y') AS dateAjout, DATE_FORMAT(dateModif, '%d %M %Y') AS dateModif FROM `chapitre` WHERE id = '$id'";
    $this->query( $sql );
  }

 /**
   * @param string $slug 
   * 
   */
  private function getDataFromSlug( $slug ){
    $sql = "SELECT id, auteur, titre, contenu, resume, slug, DATE_FORMAT(dateAjout, '%d %M %Y') AS dateAjout, DATE_FORMAT(dateModif, '%d %M %Y') AS dateModif FROM `chapitre` WHERE slug = '$slug'";
    $this->query( $sql );
  }

 /**
   * @param int $start 
   * @param int $quantity
   */
  private function getList( $start , $quantity ){
    $sql = "SELECT `id` as '{{ id }}', `titre` as '{{ titre }}',`resume` as '{{ resume }}', DATE_FORMAT(dateAjout, '%d %M %Y') as '{{ dateAjout }}',`slug`  as '{{ slug }}' FROM `chapitre` ORDER BY `chapitre`.`id`  DESC LIMIT $start, $quantity";
    $this->query( $sql , true );
  }

 /**
   * @param int $id 
   * 
   */
  private function deleteChapitre( $id ){
    $sql = "DELETE FROM `chapitre` WHERE `id` = :id";
    $this->prepare( $sql , ["id"=>$id] );
  }

 /**
   * @param string $titre 
   * @param string $resume
   * @param string $slug
   * @param string $contenu
   */
  private function createChapitre( $titre , $resume , $slug , $contenu ) {
    $sql = "INSERT INTO `chapitre` (titre, auteur, resume, slug, contenu, dateAjout) VALUES (:titre, 'Jean Forteroche', :resume, :slug, :contenu, NOW())";

    $this->prepare( $sql , compact("titre", "resume", "slug", "contenu" ) );
  }

 /**
   * @param string $titre 
   * @param string $resume
   * @param string $slug
   * @param string $contenu
   */
  private function editChapitre( $titre , $resume , $slug , $contenu ) {
    $sql = "UPDATE `chapitre` SET titre = :titre,  resume = :resume, contenu = :contenu, dateModif = NOW() WHERE slug = '$slug' ";

    $this->prepare( $sql , compact( "titre" , "resume" , "contenu" ) );

  }
}