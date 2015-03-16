<?php
// src/SkyResource/GetJobInGermanyBundle/Form/EnquiryType.php

namespace SkyResource\GetJobInGermanyBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class EnquiryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text');
        $builder->add('email', 'email', array('label' => 'E-mail'));
        $builder->add('message', 'textarea', array(
            'attr' => array('rows' => '7')));
    }

    public function getName()
    {
        return 'getjobingermany_contact';
    }
}