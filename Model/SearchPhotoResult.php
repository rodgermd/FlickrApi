<?php
/**
 * Created by JetBrains PhpStorm.
 * User: rodger
 * Date: 20.03.13
 * Time: 23:09
 * To change this template use File | Settings | File Templates.
 */

namespace Rodgermd\FlickrApi\Model;


class SearchPhotoResult
{

  protected $id;
  protected $owner;
  protected $secret;
  protected $server;
  protected $farm;
  protected $title;
  protected $is_public;

  public function __construct(\SimpleXMLElement $data)
  {
    $this->id     = (string)$data->id;
    $this->owner  = (string)$data->owner;
    $this->secret = (string)$data->secret;
    $this->server = (string)$data->server;
    $this->farm   = (string)$data->farm;
    $this->title  = (string)$data->title;
  }

  public function getTitle()
  {
    return $this->title;
  }

  public function getThumbnail($size = 'm')
  {
    return 'http://farm' . $this->farm . '.static.flickr.com/' . $this->server . '/' . $this->id . '_' . $this->secret . '_' . $size . '.jpg';
  }
}