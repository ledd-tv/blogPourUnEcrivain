<?php
namespace App\Model\Entity;


class Image {

  public $id;

  public $title;

  public $alt;

  public $url;

  public function getImage(){
    return '/images/'. $this->url;
  }



}
