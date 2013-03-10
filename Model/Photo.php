<?php

namespace Ideato\FlickrApi\Model;

class Photo
{
    private $id;
    private $photoset_id;
    private $url;
    private $title;
    private $preview;
    private $image;
    private $description;


    public function getPhotoSetId()
    {
        return $this->photoset_id;
    }

    public function setPhotoSetId($photoset_id)
    {
        $this->photoset_id = $photoset_id;
    }


    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getPreview()
    {
        return $this->preview;
    }

    public function setPreview($preview)
    {
        $this->preview = $preview;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }
}

