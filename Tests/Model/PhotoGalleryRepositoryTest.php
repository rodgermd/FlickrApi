<?php

namespace Rodgermd\FlickrApi\Tests\Model;

use Rodgermd\FlickrApi\Model\PhotoGalleryRepository;

/**
 * @group flickr
 */
class PhotoGalleryRepositoryTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->flickr_api = $this->getMock('Rodgermd\FlickrApi\Wrapper\FlickrApi', array('getPhotoSets', 'getPhotoSet', 'getRecentPhotos', 'getAllContexts'), array(), '', false);
        $this->photo_repository = $this->getMock('Rodgermd\FlickrApi\Model\PhotoRepository', array('getPhotosFromXml'));

        $this->photogallery_repository = new PhotoGalleryRepository($this->flickr_api, $this->photo_repository);
    }


    public function testGetPhotoGalleriesPreview()
    {
        $xml_mock_sets_results = \simplexml_load_file(__DIR__.'/../../DataFixtures/Files/flickr_api_get_sets_results.xml');

        $this->flickr_api->expects($this->any())
                   ->method('getPhotoSets')
                   ->will($this->returnValue($xml_mock_sets_results));

        $photogalleries = $this->photogallery_repository->getPhotoGalleriesPreview();

        $this->assertEquals(6, count($photogalleries));
        foreach ($photogalleries as $photogallery)
        {
            $this->assertTrue($photogallery instanceof \Rodgermd\FlickrApi\Model\PhotoGallery);
        }

        $this->assertEquals('72157623940754473', $photogalleries[0]->getId());
        $this->assertEquals('Flickr cup 1', $photogalleries[0]->getTitle());
        $this->assertEquals('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean at orci in nisl commodo malesuada. Ut varius, sem vel tempus elementum, nisl tortor tincidunt diam, et eleifend libero est vel nunc.', $photogalleries[0]->getDescription());
        $this->assertEquals('http://farm2.static.flickr.com/1134/4609025198_196fbbd66d_m.jpg', $photogalleries[0]->getPreview());
        $this->assertEquals(array(), $photogalleries[0]->getPhotos());
    }

    public function testGetPhotoGallery()
    {
        $xml_mock_set_result = \simplexml_load_file(__DIR__.'/../../DataFixtures/Files/flickr_api_get_set_result.xml');

        $this->flickr_api->expects($this->any())
                   ->method('getPhotoSet')
                   ->with('72157623940754473')
                   ->will($this->returnValue($xml_mock_set_result));

        $this->photo_repository->expects($this->any())
                         ->method('getPhotosFromXml')
                         ->will($this->returnValue(array()));

        $photogallery = $this->photogallery_repository->getPhotoGallery('72157623940754473');

        $this->assertTrue($photogallery instanceof \Rodgermd\FlickrApi\Model\PhotoGallery);

        $this->assertEquals('72157623940754473', $photogallery->getId());
        $this->assertEquals('Flickr cup 1', $photogallery->getTitle());
        $this->assertEquals('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean at orci in nisl commodo malesuada. Ut varius, sem vel tempus elementum, nisl tortor tincidunt diam, et eleifend libero est vel nunc.', $photogallery->getDescription());
        $this->assertEquals(0, count($photogallery->getPhotos()));
    }

    public function testGetLatestPhotos()
    {
        $xml_latest_photos = \simplexml_load_file(__DIR__.'/../../DataFixtures/Files/latest_photos.xml');
        $xml_all_contexts = \simplexml_load_file(__DIR__.'/../../DataFixtures/Files/all_contexts.xml');

        $photo1 = $this->getMock('Rodgermd\FlickrApi\Model\Photo', array('setPhotoSetId', 'getId'));
        $photo1->expects($this->once())
               ->method('setPhotoSetId')
               ->with('72157623940754473');
        $photo1->expects($this->once())
               ->method('getId')
               ->will($this->returnValue('1234'));

        $photo2 = $this->getMock('Rodgermd\FlickrApi\Model\Photo', array('setPhotoSetId', 'getId'));
        $photo2->expects($this->once())
               ->method('setPhotoSetId')
               ->with('72157623940754473');
        $photo1->expects($this->once())
               ->method('getId')
               ->will($this->returnValue('4321'));

        $photos = array($photo1, $photo2);

        $this->photo_repository->expects($this->any())
                         ->method('getPhotosFromXml')
                         ->will($this->returnValue($photos));

        $this->flickr_api->expects($this->once())
                   ->method('getRecentPhotos')
                   ->will($this->returnValue($xml_latest_photos));
        
        $this->flickr_api->expects($this->exactly(2))
                   ->method('getAllContexts')
                   ->will($this->onConsecutiveCalls($xml_all_contexts, $xml_all_contexts));

        $photos = $this->photogallery_repository->getLatestPhotos();

        $this->assertEquals(2, count($photos));
    }
}
