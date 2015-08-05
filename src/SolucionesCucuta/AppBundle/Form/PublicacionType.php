<?php

namespace SolucionesCucuta\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PublicacionType extends BasicType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titulo')
            ->add('subtitulo')
            ->add('tipo')
            ->add('estado')
            ->add('etiquetas')
        ;
        if($this->search){
            $builder
                ->add('contenido')
                ->add('slug')
            ;
        }else{
            $builder
                ->add('contenido', null, array(
                    'attr' => array(
                        'data-uk-htmleditor' => '{markdown:true, lblPreview: \'Vista Previa\', lblCodeview: \'Código\'}'
                    )
                ))
            ;
        }
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SolucionesCucuta\AppBundle\Entity\Publicacion'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'solucionescucuta_appbundle_publicacion';
    }
}
