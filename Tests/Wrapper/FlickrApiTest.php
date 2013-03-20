<?php

namespace Rodgermd\FlickrApi\Tests\Wrapper;

use Rodgermd\FlickrApi\Test\CurlMock;
use Rodgermd\FlickrApi\Wrapper\FlickrApi;

/**
 * @group flickr
 */
class FlickrApiTest extends \PHPUnit_Framework_TestCase
{
    protected $wrapper;

    public function setUp()
    {
        $this->wrapper = new FlickrApi(new CurlMock(), 'http://api.flickr.com/services/rest/?', '44774306@N00', 'a6d472134d5877b51a38070c7c631956');
    }

    public function testGetPhotoSets()
    {
        $photo_sets = $this->wrapper->getPhotoSets();
        $this->assertEquals(6, \count($photo_sets));

        $this->assertEquals('72157623940754473', (string)$photo_sets[0]->id);
        $this->assertEquals('Flickr cup 1', (string)$photo_sets[0]->title);
        $this->assertEquals('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean at orci in nisl commodo malesuada. Ut varius, sem vel tempus elementum, nisl tortor tincidunt diam, et eleifend libero est vel nunc.', (string)$photo_sets[0]->description);
        $this->assertEquals('http://farm2.static.flickr.com/1134/4609025198_196fbbd66d_m.jpg', (string)$photo_sets[0]->preview);
    }

    public function testGetPhotoSetPreview()
    {
        $photo_url = $this->wrapper->getPhotoSetPreview(array('id' => '72157623940754473', 'primary' => '4609025198', 'secret' => '196fbbd66d', 'server' => '1134', 'farm' => '2'));
        
        $this->assertEquals('http://farm2.static.flickr.com/1134/4609025198_196fbbd66d_m.jpg', $photo_url);
    }

    public function testGetSinglePhotoSet()
    {
        $photo_set = $this->wrapper->getPhotoSet('72157623940754473');

        $this->assertEquals('Flickr cup 1', (string)$photo_set->title);
        $this->assertEquals('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean at orci in nisl commodo malesuada. Ut varius, sem vel tempus elementum, nisl tortor tincidunt diam, et eleifend libero est vel nunc.', (string)$photo_set->description);

        $attributes = $photo_set->photos->photo[0]->attributes();

        $this->assertEquals('Who\'s taking pictures of who?', (string)$attributes['title']);
        $this->assertEquals('', (string)$attributes['description']);
        $this->assertEquals('4609025198', (string)$attributes['id']);
        $this->assertEquals('wpf', (string)$attributes['pathalias']);
        $this->assertEquals('http://farm2.static.flickr.com/1134/4609025198_196fbbd66d.jpg', (string)$attributes['url_m']);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function test_wrong_construct_parameters()
    {
        $curl = $this->getMock('\Rodgermd\FlickrApi\Wrapper\Curl');
        $wrapper = new FlickrApi($curl, '', '', '');
    }

    public function test_no_photo_sets()
    {
        $curl = $this->getMock('\Rodgermd\FlickrApi\Wrapper\Curl', array('get'));
        $curl->expects($this->any())
             ->method('get')
             ->will($this->returnValue('<?xml version="1.0" encoding="utf-8" ?><rsp stat="ok"><photosets></photosets></rsp>'));

        $wrapper = new FlickrApi($curl, 'http://www.example.com', '123', '1234');
        $this->assertEquals(array(), $wrapper->getPhotoSets());
    }

    public function test_empty_response()
    {
        $curl = $this->getMock('\Rodgermd\FlickrApi\Wrapper\Curl', array('get'));
        $curl->expects($this->any())
             ->method('get')
             ->will($this->returnValue(''));

        $wrapper = new FlickrApi($curl, 'http://www.example.com', '123', '1234');
        $this->assertEquals(array(), $wrapper->getPhotoSets());

        $this->assertEquals(null, $wrapper->getPhotoSet('12345'));
    }

    public function test_error_response()
    {
        $curl = $this->getMock('\Rodgermd\FlickrApi\Wrapper\Curl', array('get'));
        $curl->expects($this->any())
             ->method('get')
             ->will($this->returnValue('<?xml version="1.0" encoding="utf-8" ?><rsp stat="fail"><err code="1" msg="Photoset not found" /></rsp>'));

        $wrapper = new FlickrApi($curl, 'http://www.example.com', '123', '1234');

        $this->assertEquals(array(), $wrapper->getPhotoSets());

        $curl = $this->getMock('\Rodgermd\FlickrApi\Wrapper\Curl', array('get'));
        $curl->expects($this->any())
             ->method('get')
             ->will($this->returnValue('<?xml version="1.0" encoding="utf-8" ?><rsp stat="fail"><err code="1" msg="Photoset not found" /></rsp>'));

        $wrapper = new FlickrApi($curl, 'http://www.example.com', '123', '1234');

        $this->assertEquals(null, $wrapper->getPhotoSet('12345'));

        
        $curl = $this->getMock('\Rodgermd\FlickrApi\Wrapper\Curl', array('get'));
        $curl->expects($this->any())
             ->method('get')
             ->will($this->onConsecutiveCalls('<?xml version="1.0" encoding="utf-8" ?><rsp stat="ok"></rsp>',
                                              '<?xml version="1.0" encoding="utf-8" ?><rsp stat="fail"></rsp>'));

        $wrapper = new FlickrApi($curl, 'http://www.example.com', '123', '1234');

        $this->assertEquals(null, $wrapper->getPhotoSet('12345'));
    }

    public function test_curl_error()
    {
        $curl = $this->getMock('\Rodgermd\FlickrApi\Wrapper\Curl', array('get', 'hasError'));
        $curl->expects($this->any())
             ->method('get')
             ->will($this->returnValue('<?xml version="1.0" encoding="utf-8" ?><rsp stat="ok"></rsp>'));

        $curl->expects($this->any())
             ->method('hasError')
             ->will($this->returnValue(true));

        $wrapper = new FlickrApi($curl, 'http://www.example.com', '123', '1234');

        $this->assertEquals(array(), $wrapper->getPhotoSets());
        $this->assertEquals(null, $wrapper->getPhotoSet('12345'));
    }


    public function testGetRecentPhotos()
    {
        $expected = \simplexml_load_string(\file_get_contents(__DIR__.'/../../DataFixtures/Files/latest_photos.xml'));
        $photos_xml = $this->wrapper->getRecentPhotos();

        $this->assertEquals($expected, $photos_xml);
    }

    public function testGetRecentPhotosButNoPhotos()
    {
        $wrapper = new FlickrApi(new CurlMock(), 'http://ex.com?', 'empty', 'a');
        $this->assertEquals(null, $wrapper->getRecentPhotos());
    }

    public function testGetAllContexts()
    {
        $expected = \simplexml_load_string(\file_get_contents(__DIR__.'/../../DataFixtures/Files/all_contexts.xml'));
        $all_contexts = $this->wrapper->getAllContexts('5513307106');

        $this->assertEquals($expected, $all_contexts);
    }

    public function testGetAllContextsNoAnswer()
    {
        $all_contexts = $this->wrapper->getAllContexts('no_photo_id');

        $this->assertEquals(null, $all_contexts);
    }
    
    public function testGetAllContextsNoSets()
    {
        $expected = \simplexml_load_string(\file_get_contents(__DIR__.'/../../DataFixtures/Files/all_contexts_no_sets.xml'));
        $all_contexts = $this->wrapper->getAllContexts('123456');

        $this->assertEquals($expected, $all_contexts);
    }
}
