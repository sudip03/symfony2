<?php

namespace Classified\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Description of UserType
 * 
 */

class UserType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options) 
    {       $entityManager = $options['em']; 
            
            $builder->add('firstName', 'text', array('attr' => array('class' => 'form-control')));
            $builder->add('lastName', 'text', array('attr' => array('class' => 'form-control')));
            $builder->add('screenName', 'text', array('attr' => array('class' => 'form-control')));
            $builder->add('email', 'email', array('attr' => array('class' => 'form-control')));
            
            $builder->add('password', 'password', array('attr' => array('class' => 'form-control')));
            
            $builder->add('avatar', new \Classified\UserBundle\Form\FileType(), array('required'=>false,
                                                                                  'label'     => 'Image'));

            $builder->add('save', 'submit', array('attr' => array('class' => 'btn-primary gap-height')) );
    }
     
       public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Classified\UserBundle\Entity\User',
        ));
        
        $resolver->setRequired(array(
            'em',
        ));

        $resolver->setAllowedTypes(array(
            'em' => 'Doctrine\Common\Persistence\ObjectManager',
        ));
    }
    
    /**
     * 
     * @return string
     * 
     */
    public function getName()
    {
        return 'user';
    }
}

?>
