<?php
/**
 * Created by JetBrains PhpStorm.
 * User: rodger
 * Date: 20.03.13
 * Time: 23:09
 * To change this template use File | Settings | File Templates.
 */

namespace Rodgermd\FlickrApi\Model;


class SearchPhotoResult {

  protected $id;
  protected $owner;
  protected $secret;
  protected $server;
  protected $farm;
  protected $title;
  protected $is_public;


  public function __construct(\SimpleXMLElement $xml)
  {
    $a = 1;
  }
}