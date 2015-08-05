<?php

namespace SolucionesCucuta\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ArchivoType extends BasicType
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
            ->add('tipo')
            ->add('estado')
            ->add('etiquetas')
            ->add('file')
            ->add('usuario')
            ->add('publicacion')
            ->add('link')
        ;
        if($this->search){
            $opts = array(
                'mapped' => false,
                'choices'    => array(
                    '<'  => '(<) Menor que',
                    '<=' => '(<=) Menor o igual que',
                    '>'  => '(>) Mayor que',
                    '>=' => '(>=) Mayor o igual que',
                    '='  => '(=) Igual que',
                ),
                'preferred_choices' => array('='),
                'required'  => false
            );
            $builder
                ->add('prints')
                ->add('clicks')
                ->add('slug')
                ->add('vista_o', 'choice', $opts)
                ->add('click_o','choice', $opts)
            ;
        }
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SolucionesCucuta\AppBundle\Entity\Archivo'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'solucionescucuta_appbundle_archivo';
    }
}
