<?php
// src/SkyResource/GetJobInGermanyBundle/Entity/Search.php

namespace SkyResource\GetJobInGermanyBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints\Length;

class Search
{
    protected $keyword;

    protected $location;
    

    public function getKeyword()
    {
        return $this->keyword;
    }

    public function setKeyword($keyword)
    {
        $this->keyword = $keyword;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function setLocation($location)
    {
        $this->location = $location;
    }
}