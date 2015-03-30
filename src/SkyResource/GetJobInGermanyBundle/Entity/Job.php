<?php

namespace SkyResource\GetJobInGermanyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Job
 *
 * @ORM\Entity(repositoryClass="SkyResource\GetJobInGermanyBundle\Entity\Repository\JobRepository")
 * @ORM\Table(name="jobs", indexes={@ORM\Index(name="FOREIGN", columns={"category_id"})})
 */
class Job
{
    /**
     * @var string
     *
     * @ORM\Column(name="reference_number", type="string", length=50, nullable=false)
     */
    private $referenceNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=200, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="text", length=65535, nullable=false)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="company", type="string", length=150, nullable=false)
     */
    private $company;

    /**
     * @var string
     *
     * @ORM\Column(name="company_size", type="string", length=50, nullable=false)
     */
    private $companySize;
    
    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=150, nullable=false)
     */
    private $city;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="more_cities", type="boolean", nullable=false)
     */
    private $moreCities; 
    
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="location", type="text", length=65535, nullable=false)
     */
    private $location;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="text", length=65535, nullable=false)
     */
    private $type;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="date", nullable=false)
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="published_date", type="date", nullable=false)
     */
    private $publishedDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="current_time", type="datetime", nullable=false)
     * @ORM\Version
     */
    private $currentTime;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \SkyResource\GetJobInGermanyBundle\Entity\Category
     *
     * @ORM\ManyToOne(targetEntity="SkyResource\GetJobInGermanyBundle\Entity\Category")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     * })
     */
    private $category;



    /**
     * Set referenceNumber
     *
     * @param string $referenceNumber
     * @return Jobs
     */
    public function setReferenceNumber($referenceNumber)
    {
        $this->referenceNumber = $referenceNumber;

        return $this;
    }

    /**
     * Get referenceNumber
     *
     * @return string 
     */
    public function getReferenceNumber()
    {
        return $this->referenceNumber;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Jobs
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set company
     *
     * @param string $company
     * @return Jobs
     */
    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return string 
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set companySize
     *
     * @param string $companySize
     * @return Jobs
     */
    public function setCompanySize($companySize)
    {
        $this->companySize = $companySize;

        return $this;
    }

    /**
     * Get companySize
     *
     * @return string 
     */
    public function getCompanySize()
    {
        return $this->companySize;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Jobs
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set location
     *
     * @param string $location
     * @return Jobs
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return string 
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Jobs
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     * @return Jobs
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime 
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set publishedDate
     *
     * @param \DateTime $publishedDate
     * @return Jobs
     */
    public function setPublishedDate($publishedDate)
    {
        $this->publishedDate = $publishedDate;

        return $this;
    }

    /**
     * Get publishedDate
     *
     * @return \DateTime 
     */
    public function getPublishedDate()
    {
        return $this->publishedDate;
    }

    /**
     * Set currentTime
     *
     * @param \DateTime $currentTime
     * @return Jobs
     */
    public function setCurrentTime($currentTime)
    {
        $this->currentTime = $currentTime;

        return $this;
    }

    /**
     * Get currentTime
     *
     * @return \DateTime 
     */
    public function getCurrentTime()
    {
        return $this->currentTime;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set category
     *
     * @param \SkyResource\GetJobInGermanyBundle\Entity\Category $category
     * @return Jobs
     */
    public function setCategory(\SkyResource\GetJobInGermanyBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \SkyResource\GetJobInGermanyBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Job
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return Job
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set more_cities
     *
     * @param boolean $moreCities
     * @return Job
     */
    public function setMoreCities($moreCities)
    {
        $this->moreCities = $moreCities;

        return $this;
    }

    /**
     * Get more_cities
     *
     * @return boolean
     */
    public function getMoreCities()
    {
        return $this->moreCities;
    }
    
    
    static public function getLuceneIndex()
    {
        if (file_exists($index = self::getLuceneIndexFile())) {
            return \Zend_Search_Lucene::open($index);
        }

        return \Zend_Search_Lucene::create($index);
    }

    static public function getLuceneIndexFile()
    {
        return __DIR__.'/../../../../web/data/job.index';
    }
}
