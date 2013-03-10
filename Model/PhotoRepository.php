<?php

namespace Ideato\FlickrApi\Model;

use Ideato\FlickrApi\Model\Photo;

class PhotoRepository
{
    /**
     * Builds an array of Ideato\FlickrApi\Model\Photo object based on the SimpleXMLElement given
     *
     * @param \SimpleXMLElement $xml
     * @param string $preview_size
     * @param string $image_size
     * @return array of Ideato\FlickrApi\Model\Photo object
     */
    public function getPhotosFromXml(\SimpleXMLElement $xml, $preview_size = 's', $image_size = 'm')
    {
        $photos = array();
        foreach ($xml->photo as $photo)
        {
            $attributes = $photo->attributes();

            $photo = new Photo();
            $photo->setId((string)$attributes['id']);
            $photo->setUrl('http://www.flickr.com/'.$attributes['pathalias'].'/'.$attributes['id']);
            $photo->setTitle((string)$attributes['title']);
            $photo->setDescription('');
            $photo->setPreview((string)$attributes['url_'.$preview_size]);
            $photo->setImage((string)$attributes['url_'.$image_size]);

            $photos[] = $photo;

        }

        return $photos;
    }
}
