<?php

namespace Ideato\FlickrApi\Tests\Model;

use Ideato\FlickrApi\Model\PhotoRepository;

/**
 * @group flickr
 */
class PhotoRepositoryTest extends \PHPUnit_Framework_TestCase
{
    public function testGetPhotosFromXml()
    {
        $repository = new PhotoRepository();
        $xml = \simplexml_load_file(__DIR__.'/../../DataFixtures/Files/photos.xml');

        $photos = $repository->getPhotosFromXml($xml);

        foreach ($photos as $photo)
        {
            $this->assertTrue($photo instanceof \Ideato\FlickrApi\Model\Photo);
        }

        $this->assertEquals('http://www.flickr.com/wpf/4609025198', (string)$photos[0]->getUrl());
        $this->assertEquals('Who\'s taking pictures of who?', (string)$photos[0]->getTitle());
        $this->assertEquals('http://farm2.static.flickr.com/1134/4609025198_196fbbd66d_m.jpg', (string)$photos[0]->getPreview());
        $this->assertEquals('', (string)$photos[0]->getDescription());
        $this->assertEquals('4609025198', (string)$photos[0]->getId());

    }
}
