<?php
// src/SkyResource/GetJobInGermanyBundle/Tests/Entity/SearchTest.php

namespace SkyResource\GetJobInGermanyBundle\Tests\Entity;

use SkyResource\GetJobInGermanyBundle\Entity\Search;

class SearchTest extends \PHPUnit_Framework_TestCase
{
    public function testCalculateStartDate()
    {
        $search = new Search();

        $this->assertEquals(new \DateTime("2015-07-04"), $search->calculateStartDate(2));
    }
}

?>