<?php
// src/SkyResource/GetJobInGermanyBundle/Entity/Repository/JobRepository.php

namespace SkyResource\GetJobInGermanyBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * JobRepository
 */
class JobRepository extends EntityRepository
{

    public function getLatestJobs($limit = null)
    {
      $qj = $this->createQueryBuilder('j')
                 ->select('partial j.{id,title,company,slug,publishedDate,city}')
                 ->addOrderBy('j.publishedDate', 'DESC');

      if (false === is_null($limit))
          $qj->setMaxResults($limit);

      return $qj->getQuery()
                ->getResult();
    }
    
    public function getJobsBySearchCriteria($skill, $location, $category, $currentPage=1) {
        if ($category!="") {
            $jSearchQuery = $this->createQueryBuilder('j')
                 ->select('partial j.{id,title,company,slug,publishedDate,city,moreCities}, c')
                 ->leftJoin('j.category','c')
                 ->where('j.description LIKE :skill')
                 ->orWhere('j.title LIKE :skill')
                 ->andWhere('c.slug = :category')
                 ->andWhere('j.location LIKE :location')
                 ->setParameter('skill', '%'.$skill.'%')
                 ->setParameter('location', '%'.$location.'%')
                 ->setParameter('category', $category)
                 ->addOrderBy('j.publishedDate', 'DESC')
                 ->getQuery();
        }
        else {
            $jSearchQuery = $this->createQueryBuilder('j')
                 ->select('partial j.{id,title,company,slug,publishedDate,city,moreCities}, c')
                 ->leftJoin('j.category','c')
                 ->where('j.description LIKE :skill')
                 ->orWhere('j.title LIKE :skill')
                 ->andWhere('j.location LIKE :location')
                 ->setParameter('skill', '%'.$skill.'%')
                 ->setParameter('location', '%'.$location.'%')
                 ->addOrderBy('j.publishedDate', 'DESC')
                 ->getQuery();  
        }
                
      $paginator = $this->paginate($jSearchQuery, $currentPage);

      return $paginator;
    }
    
    public function getNumberOfAllJobs() {
        $jSearchQuery = $this->createQueryBuilder('j')
             ->select('COUNT(j)')
             ->getQuery();
        return $jSearchQuery->getSingleScalarResult();
    }
    
    public function getNumberOfJobsByCategory() {
        
        $jobsByCategories = array();
        $numberOfJobs = array();
        
            
            $jSearchQuery = $this->createQueryBuilder('j')
                ->select('c.slug as slug, COUNT(j) as number_of_jobs')
                ->leftJoin('GetJobInGermanyBundle:Category','c','WITH','j.category=c')
                ->groupBy('c.slug')
                ->getQuery();
                $jobsByCategories = $jSearchQuery->getResult();
        
        foreach ($jobsByCategories as $jobsByCategory) {
            $numberOfJobs[$jobsByCategory['slug']] = $jobsByCategory['number_of_jobs'];
        }
        
        return $numberOfJobs;
    }
    
    public function getNumberOfJobsByCity($cities) {
        
        $numberOfJobs = array();
        
        foreach ($cities as $city) {
            $jSearchQuery = $this->createQueryBuilder('j')
                ->select('COUNT(j)')
                ->where('j.location LIKE :city')
                ->setParameter('city', '%'.$city.'%')
                ->getQuery();
            $numberOfJobs[$city] = $jSearchQuery->getSingleScalarResult();
        }
        
        return $numberOfJobs;
    
    }
    
/*    public function getJobsNumberBySearchCriteria($skill, $location, $currentPage=1) {
      $jSearchQuery = $this->createQueryBuilder('j')
                 ->select('COUNT(j)')
                 ->where('j.description LIKE :skill')
                 ->orWhere('j.title LIKE :skill')
                 ->andWhere('j.location LIKE :location')
                 ->setParameter('skill', '%'.$skill.'%')
                 ->setParameter('location', '%'.$location.'%')
                 ->addOrderBy('j.publishedDate', 'DESC')
                 ->getQuery();

      return jSearchQuery->getSingleScalarResult();
    }

    
    public function getJobsByCategory($category, $currentPage = 1)
    {
    
      $jcQuery = $this->createQueryBuilder('j')
                ->select('j', 'c')
                 ->leftJoin('j.category','c')
                 ->where('c.slug = :slug')
                 ->setParameter('slug', $category)
                 ->addOrderBy('j.publishedDate', 'DESC')
                 ->getQuery();
        
      $paginator = $this->paginate($jcQuery, $currentPage);

      return $paginator;
    }
*/
    
    public function getJobsByCity($city, $currentPage = 1)
    {
    
      $joQuery = $this->createQueryBuilder('j')
                ->select('partial j.{id,title,company,slug,publishedDate,city,moreCities}')
                 ->where('j.location LIKE :city')
                 ->setParameter('city', '%'.$city.'%')
                 ->addOrderBy('j.publishedDate', 'DESC')
                 ->getQuery();
        
      $paginator = $this->paginate($joQuery, $currentPage);

      return $paginator;
    }
    
/*
    public function getJobsByCategoryAndCity($category, $city, $currentPage = 1) {
            
      $jccQuery = $this->createQueryBuilder('j')
                 ->select('j','c')
                 ->leftJoin('j.category','c')
                  ->where('c.slug = :slug')
                 ->andWhere('j.location LIKE :city')
                 ->setParameter('slug', $category)
                 ->setParameter('city', '%'.$city.'%')
                 ->addOrderBy('j.publishedDate', 'DESC')
                 ->getQuery();
        
      $paginator = $this->paginate($jccQuery, $currentPage);

      return $paginator;
    }
    
*/
    public function paginate($dql, $page = 1, $limit = 30)
    {
      $paginator = new Paginator($dql);

      $paginator->getQuery()
          ->setFirstResult($limit * ($page - 1)) // Offset
          ->setMaxResults($limit); // Limit

      return $paginator;
    }

}