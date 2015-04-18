<?php
// src/SkyResource/GetJobInGermanyBundle/Entity/Repository/JobRepository.php

namespace SkyResource\GetJobInGermanyBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use SkyResource\GetJobInGermanyBundle\Entity\Job;


/**
 * JobRepository
 */
class JobRepository extends EntityRepository
{
    // return latest jobs (array) when maximal number (limit) of jobs is given; when limit is null, return all of them
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
    
    // return all jobs, but get only particular job properties (id, title, company, slug, publishedDate, city, moreCities)
    public function getAllJobs() {
        
        $jAllQuery = $this->createQueryBuilder('j')
                 ->select('partial j.{id,title,company,slug,publishedDate,city,moreCities}')
                 ->addOrderBy('j.publishedDate', 'DESC')
                 ->getQuery();
        
        $paginator = $this->paginate($jAllQuery, $currentPage);
        
        return $paginator;
        
    }
    
    // return Query when $category is not set, using $skill and $location string parameters which can be empty - there are 4 posible variants
    private function getQueryWithoutCategory($skill, $location) {
        if ($skill!="" && $location!="") {
            $jSearchQuery = $this->createQueryBuilder('j')
                 ->select('partial j.{id,title,company,slug,publishedDate,city,moreCities}')
                 ->where('MATCH(j.location) AGAINST(:location) > 0.8')
                 ->andWhere('MATCH(j.title,j.description) AGAINST(:skill) > 0.8')
                 ->setParameter('skill', $skill)
                 ->setParameter('location', $location)
                 ->addOrderBy('j.publishedDate', 'DESC')
                 ->getQuery();
        }
        else if ($skill!="" && $location=="") {
            $jSearchQuery = $this->createQueryBuilder('j')
                 ->select('partial j.{id,title,company,slug,publishedDate,city,moreCities}')
                 ->where('MATCH(j.title,j.description) AGAINST(:skill) > 0.8')
                 ->setParameter('skill', $skill)
                 ->addOrderBy('j.publishedDate', 'DESC')
                 ->getQuery();
            
        }
        else if ($skill=="" && $location!="") {
            $jSearchQuery = $this->createQueryBuilder('j')
                 ->select('partial j.{id,title,company,slug,publishedDate,city,moreCities}')
                 ->where('MATCH(j.location) AGAINST(:location) > 0.8')
                 ->setParameter('location', $location)
                 ->addOrderBy('j.publishedDate', 'DESC')
                 ->getQuery();            
        }
        else {
            $jSearchQuery = $this->createQueryBuilder('j')
                 ->select('partial j.{id,title,company,slug,publishedDate,city,moreCities}')
                 ->addOrderBy('j.publishedDate', 'DESC')
                 ->getQuery();     
        }
        
        return $jSearchQuery;
    }
    
    // return Query when $category is set, using $skill and $location string parameters which can be empty - there are 4 posible variants
    private function getQueryWithCategory($skill, $location, $category) {
        if ($skill!="" && $location!="") {
            $jSearchQuery = $this->createQueryBuilder('j')
                 ->select('partial j.{id,title,company,slug,publishedDate,city,moreCities}, c')
                 ->leftJoin('j.category','c')
                 ->where('c.slug = :category')
                 ->andWhere('MATCH(j.location) AGAINST(:location) > 0.8')
                 ->andWhere('MATCH(j.title,j.description) AGAINST(:skill) > 0.8')
                 ->setParameter('skill', $skill)
                 ->setParameter('location', $location)
                 ->setParameter('category', $category)
                 ->addOrderBy('j.publishedDate', 'DESC')
                 ->getQuery();
        }
        else if ($skill!="" && $location=="") {
            $jSearchQuery = $this->createQueryBuilder('j')
                 ->select('partial j.{id,title,company,slug,publishedDate,city,moreCities}, c')
                 ->leftJoin('j.category','c')
                 ->where('c.slug = :category')
                 ->andWhere('MATCH(j.title,j.description) AGAINST(:skill) > 0.8')
                 ->setParameter('skill', $skill)
                 ->setParameter('category', $category)
                 ->addOrderBy('j.publishedDate', 'DESC')
                 ->getQuery();
        }
        else if ($skill=="" && $location!="") {
            $jSearchQuery = $this->createQueryBuilder('j')
                 ->select('partial j.{id,title,company,slug,publishedDate,city,moreCities}, c')
                 ->leftJoin('j.category','c')
                 ->where('c.slug = :category')
                 ->andWhere('MATCH(j.location) AGAINST(:location) > 0.8')
                 ->setParameter('location', $location)
                 ->setParameter('category', $category)
                 ->addOrderBy('j.publishedDate', 'DESC')
                 ->getQuery();
        }
        else {
            $jSearchQuery = $this->createQueryBuilder('j')
                 ->select('partial j.{id,title,company,slug,publishedDate,city,moreCities}, c')
                 ->leftJoin('j.category','c')
                 ->where('c.slug = :category')
                 ->setParameter('category', $category)
                 ->addOrderBy('j.publishedDate', 'DESC')
                 ->getQuery();        
        }
        
        return $jSearchQuery;
    }
    
    // return "paginated" jobs using search criteria - parameters: skill (keyword), location, category and current page (for pagination)
    public function getJobsBySearchCriteria($skill, $location, $category, $currentPage=1) {
        
        if ($category=="") {
            $jSearchQuery = $this->getQueryWithoutCategory($skill,$location);
        }
        
        else {
            $jSearchQuery = $this->getQueryWithCategory($skill,$location,$category);
        }

      $paginator = $this->paginate($jSearchQuery, $currentPage);

      return $paginator;
    }
    
    // return number of all jobs in the database
    public function getNumberOfAllJobs() {
        $jSearchQuery = $this->createQueryBuilder('j')
             ->select('COUNT(j)')
             ->getQuery();
        return $jSearchQuery->getSingleScalarResult();
    }
    
    // return numbers of jobs for each category
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
    
    // return numbers of jobs per city, when city is given as parameter
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
    
    // return paginated jobs per city, when city is given as parameter
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
    
    // help function for pagination
    public function paginate($dql, $page = 1, $limit = 30)
    {
      $paginator = new Paginator($dql);

      $paginator->getQuery()
          ->setFirstResult($limit * ($page - 1)) // Offset
          ->setMaxResults($limit); // Limit

      return $paginator;
    }
    
    // return similar jobs, when part of the keyword is given (keywordPart)
    public function getSimilarJobs($keywordPart) {
        
        $qj = $this->createQueryBuilder('j')
                 ->select('j.title')
                 ->where('j.title LIKE :keywordPart')
                 ->setParameter('keywordPart', '%'.$keywordPart.'%')
                 ->distinct()
        /*         ->addOrderBy('j.publishedDate', 'DESC')  */
                 ->setMaxResults(10);

        return $qj->getQuery()
                ->getResult();
    }
    
    // return similar jobs, when part of the city string is given (locationPart)    
    public function getSimilarLocations($locationPart) {
       
       $qj = $this->createQueryBuilder('j')
                 ->select('j.city')
                 ->where('j.city LIKE :locationPart')
                 ->setParameter('locationPart', '%'.$locationPart.'%')
                 ->distinct()
                 ->setMaxResults(10);

        return $qj->getQuery()
                ->getResult();
    }
    
}