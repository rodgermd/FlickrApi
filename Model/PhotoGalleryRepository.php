<?php

namespace Ideato\FlickrApi\Model;

use Ideato\FlickrApi\Model\PhotoGallery;

class PhotoGalleryRepository
{
    protected $flickr_api;
    protected $photo_repository;

    public function __construct(\Ideato\FlickrApi\Wrapper\FlickrApi $flickr_api,
                                \Ideato\FlickrApi\Model\PhotoRepository $photo_respository)
    {
        $this->flickr_api = $flickr_api;
        $this->photo_repository = $photo_respository;
    }

    /**
     * Given a SimpleXMLElement, hydrate the data fo a Photogallery
     *
     * @param \SimpleXMLElement $album
     * @return PhotoGallery
     */
    protected function buildPhotogalleryFromXml(\SimpleXMLElement $album)
    {
        $photogallery = new PhotoGallery();
        $photogallery->setId((string)$album->id);
        $photogallery->setTitle((string)$album->title);
        $photogallery->setDescription((string)$album->description);
        $photogallery->setPreview((string)$album->preview);

        return $photogallery;
    }

    /**
     * Using the Flickr API class retrieves the photo sets and return an array of PhotoGallery objects
     *
     * @return array
     */
    public function getPhotoGalleriesPreview()
    {
        $photogalleries = array();
        $albums_xml = $this->flickr_api->getPhotoSets();

        foreach ($albums_xml as $album)
        {
            $photogalleries[] = $this->buildPhotogalleryFromXml($album);
        }

        return $photogalleries;
    }

    /**
     * Using the Flickr API class retrieves the photo set and its images,
     * and return a PhotoGallery with its Photo objects associated
     *
     * @param string $photogallery_id
     * @return PhotoGallery
     */
    public function getPhotoGallery($photogallery_id, $preview_size = 'sq', $image_size = 'sq')
    {
        $album_xml = $this->flickr_api->getPhotoSet($photogallery_id);

        if ($album_xml)
        {
            $photogallery = $this->buildPhotogalleryFromXml($album_xml);
            $photogallery->setId($photogallery_id);
            $photogallery->setPhotos($this->photo_repository->getPhotosFromXml($album_xml->photos, $preview_size, $image_size));

            return $photogallery;
        }
    }

    /**
     * Retrives the latest photos data and their contexts throught the FlickrApi object,
     * and then sets the PhotoSetId for each photo.
     *
     * @param int $limit
     * @return array
     */
    public function getLatestPhotos($limit = 9, $preview_size = 'sq', $image_size = 'sq')
    {
        $photos_xml = $this->flickr_api->getRecentPhotos($limit);
        if (\is_null($photos_xml))
        {
            return array();
        }
        
        $photos = $this->photo_repository->getPhotosFromXml($photos_xml, $preview_size, $image_size);
        foreach ($photos as $photo)
        {
            $contexts = $this->flickr_api->getAllContexts($photo->getId());

            if (isset($contexts->set))
            {
                $attributes = $contexts->set->attributes();
                $photo->setPhotoSetId((string)$attributes['id']);
            }
        }

        return $photos;
    }
}
