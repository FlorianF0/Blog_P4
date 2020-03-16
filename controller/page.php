<?php

/**
 * Class Page
 *
 * Permet de générer le contenu final front et back
 */
class Page
{

  public $html;
  public $titre;
  public $specialHeader = null;

  protected function footer(){
    return file_get_contents( "./templates/partialFooter.html" );
  }

    /**
   * @return string $html
   *
   */
  protected function renderPage(){
    $this->html  = new View(
      [
        "{{ titre }}"                => $this->titre,
        "{{ partialMain }}"          => $this->html,
      ],
      "template"
    );
  }

  protected function page404(){
    $this->html = file_get_contents( "./templates/partialError404.html" );
  }
}