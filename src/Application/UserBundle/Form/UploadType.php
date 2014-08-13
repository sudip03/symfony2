<?php

namespace Classified\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Description of UploadType
 * 
 */
class UploadType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        
        $builder->add('file', 'file', array(
            'label' => false
        ));
    }


    /**
     * 
     * @return string
     * 
     */
    public function getName() {
        return 'upload';
    }

}
