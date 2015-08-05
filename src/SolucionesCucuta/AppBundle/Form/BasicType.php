<?php

namespace SolucionesCucuta\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BasicType extends AbstractType
{
    protected $search;

    public function __construct ($search = false){
        $this->search = $search;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /*$builder
            ->add('nombre')
            ->add('descripcion')
            ->add('tipo')
            ->add('estado')
            ->add('etiquetas')
            ->add('file')
            ->add('usuario')
            ->add('publicacion')
            ->add('link')
        ;
        if($this->search){
            $builder
                ->add('prints')
                ->add('clicks')
                ->add('slug')
                ->add('vita_')
            ;
        }*/
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        /*$resolver->setDefaults(array(
            'data_class' => 'SolucionesCucuta\AppBundle\Entity\Archivo'
        ));*/
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'solucionescucuta_appbundle_basic';
    }
}
