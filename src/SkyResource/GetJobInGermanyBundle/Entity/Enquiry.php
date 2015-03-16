<?php
// src/SkyResource/GetJobInGermanyBundle/Entity/Enquiry.php

namespace SkyResource\GetJobInGermanyBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;

class Enquiry
{
    protected $name;

    protected $email;

    protected $message;
    
    
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('name', new NotBlank());

        $metadata->addPropertyConstraint('email', new Email(array('message'=>'we dont like invalid emails. please type valid one.')));
        
        $metadata->addPropertyConstraint('message', new NotBlank());

        $metadata->addPropertyConstraint('message', new Length(array('min'=>5)));
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }
}