<?php

namespace SolucionesCucuta\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EstadoType extends BasicType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('descripcion')
            /*->add('createdat')
            ->add('updatedat')*/
        ;
        if($this->search){
            $builder
                ->add('slug')
            ;
        }
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SolucionesCucuta\AppBundle\Entity\Estado'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'solucionescucuta_appbundle_estado';
    }
}
