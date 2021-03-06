<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CreateDepartmentType extends AbstractType {
	
    public function buildForm(FormBuilderInterface $builder, array $options) {
		 
		$builder
            ->add('name', 'text', array(
				'label' => 'Navn',
			))
			->add('short_name', 'text', array(
				'label' => 'Forkortet navn',
			))
			->add('email', 'text', array(
				'label' => 'E-post',
			))
			->add('address', 'text', array(
				'label' => 'Adresse:',
			))
			->add('save', 'submit', array(
				'label' => 'Opprett',
			));
    }
	
	public function setDefaultOptions(OptionsResolverInterface $resolver) {
	
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Department',
        ));
		
    }
	
    public function getName() {
        return 'createDepartment';
    }
	
}