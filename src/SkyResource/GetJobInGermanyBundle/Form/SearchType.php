<?php
// src/SkyResource/GetJobInGermanyBundle/Form/SearchType.php

namespace SkyResource\GetJobInGermanyBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('keyword', 'text', array('label' => 'Keyword or position (in German)', 'required' => false));
        $builder->add('location', 'search', array('required' => false));
    }

    public function getName()
    {
        return null;
    }
}