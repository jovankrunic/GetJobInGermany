<?php
// src/SkyResource/GetJobInGermanyBundle/Entity/Repository/CategoryRepository.php

namespace SkyResource\GetJobInGermanyBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * CategoryRepository
 */
class CategoryRepository extends EntityRepository
{
    // return category name (string) when it's slug is given
    public function getCategoryName($slug)
    {
      $cn = $this->createQueryBuilder('c')
                 ->select('c.name')
                 ->where('c.slug = :slug')
                 ->setParameter('slug', $slug);
       
      return $cn->getQuery()
                ->getSingleScalarResult();
    }
}