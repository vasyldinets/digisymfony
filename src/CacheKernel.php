<?php
namespace App;

use Symfony\Bundle\FrameworkBundle\HttpCache\HttpCache;

class CacheKernel extends HttpCache{

    protected function getOptions(){

        return array(
            'default_ttl' => 3600,
            'allow_reload' => true,
        );
    }

}