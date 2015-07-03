<?php
// src/SkyResource/GetJobInGermanyBundle/Entity/Search.php

namespace SkyResource\GetJobInGermanyBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints\Length;

class Search
{
    protected $keyword;

    protected $location;
    
    protected $timeLimitVal;
    
    protected $useTimeLimit;
    

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
    
    public function gettimeLimitVal()
    {
        return $this->timeLimitVal;
    }

    public function setTimeLimitVal($timeLimitVal)    {
        
        $this->timeLimitVal = $timeLimitVal;
    }
    
    public function getUseTimeLimit()
    {
        return $this->useTimeLimit;
    }

    public function setUseTimeLimit($useTimeLimit)
    {
        $this->useTimeLimit = $useTimeLimit;
    }
}