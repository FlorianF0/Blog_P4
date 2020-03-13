<?php

class Page
{

  public $html;
  public $titre;
  public $specialHeader = null;

  protected function footer(){
    return file_get_contents( "./templates/partialFooter.html" );
  }

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