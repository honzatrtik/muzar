<?php

namespace Muzar\BazaarBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContactType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', 'email')
            ->add('name', 'text', array(
				'required'  => false
			))
            ->add('phone', 'text', array(
				'required'  => false
			))
            ->add('lat', 'hidden', array(
				'required'  => false
			))
            ->add('lng', 'hidden', array(
				'required'  => false
			))
            ->add('place', 'hidden', array(
				'required'  => false
			))
            ->add('reference', 'hidden', array(
				'required'  => false
			))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Muzar\BazaarBundle\Entity\Contact'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'contact';
    }
}
