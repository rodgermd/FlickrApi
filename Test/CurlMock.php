<?php

namespace Rodgermd\FlickrApi\Test;

use Rodgermd\FlickrApi\Wrapper\Curl;

class CurlMock extends Curl
{
    public function get($url)
    {
        switch ($url)
        {
            case 'http://api.flickr.com/services/rest/?method=flickr.photosets.getList&api_key=a6d472134d5877b51a38070c7c631956&user_id=44774306@N00':
                return \file_get_contents(__DIR__.'/../DataFixtures/Files/albums.xml');
                break;
            case 'http://api.flickr.com/services/rest/?method=flickr.photosets.getPhotos&api_key=a6d472134d5877b51a38070c7c631956&user_id=44774306@N00&photoset_id=72157623940754473&per_page=1&page=1':
                return \file_get_contents(__DIR__.'/../DataFixtures/Files/photo_set_preview.xml');
                break;
            case 'http://api.flickr.com/services/rest/?method=flickr.photosets.getPhotos&api_key=a6d472134d5877b51a38070c7c631956&user_id=44774306@N00&photoset_id=72157623940754473&extras=path_alias,url_sq,url_t,url_s,url_l,url_m,url_o':
                return \file_get_contents(__DIR__.'/../DataFixtures/Files/photo_set.xml');
                break;
            case 'http://api.flickr.com/services/rest/?method=flickr.photosets.getInfo&api_key=a6d472134d5877b51a38070c7c631956&user_id=44774306@N00&photoset_id=72157623940754473':
                return \file_get_contents(__DIR__.'/../DataFixtures/Files/photo_set_info.xml');
                break;
            case 'http://api.flickr.com/services/rest/?method=flickr.photos.search&api_key=a6d472134d5877b51a38070c7c631956&user_id=44774306@N00&per_page=9&extras=path_alias,url_sq,url_b,url_t,url_s,url_m,url_z,url_l,url_o':
                return \file_get_contents(__DIR__.'/../DataFixtures/Files/recent_photos.xml');
                break;
            case 'http://api.flickr.com/services/rest/?method=flickr.photos.search&api_key=76d57011e657bdc87d39202e27c5ef90&user_id=63892273@N06&per_page=12&extras=path_alias,url_sq,url_b,url_t,url_s,url_m,url_z,url_l,url_o':
                return \file_get_contents(__DIR__.'/../DataFixtures/Files/recent_12_photos.xml');
                break;
            case 'http://api.flickr.com/services/rest/?method=flickr.photos.search&api_key=76d57011e657bdc87d39202e27c5ef90&user_id=63892273@N06&per_page=3&extras=path_alias,url_b,url_sq,url_t,url_s,url_m,url_z,url_l,url_o':
            case 'http://api.flickr.com/services/rest/?method=flickr.photos.search&api_key=76d57011e657bdc87d39202e27c5ef90&user_id=63892273@N06&per_page=3&extras=path_alias,url_sq,url_b,url_t,url_s,url_m,url_z,url_l,url_o':
                return \file_get_contents(__DIR__.'/../DataFixtures/Files/recent_3_photos.xml');
                break;
            case 'http://api.flickr.com/services/rest/?method=flickr.photos.getAllContexts&api_key=a6d472134d5877b51a38070c7c631956&photo_id=5513307106':
            case 'http://api.flickr.com/services/rest/?method=flickr.photos.getAllContexts&api_key=76d57011e657bdc87d39202e27c5ef90&photo_id=5513307106':
                return \file_get_contents(__DIR__.'/../DataFixtures/Files/all_contexts.xml');
                break;
            case 'http://api.flickr.com/services/rest/?method=flickr.photos.getAllContexts&api_key=a6d472134d5877b51a38070c7c631956&photo_id=123456':
                return \file_get_contents(__DIR__.'/../DataFixtures/Files/all_contexts_no_sets.xml');
                break;
        }
        
    }
}
