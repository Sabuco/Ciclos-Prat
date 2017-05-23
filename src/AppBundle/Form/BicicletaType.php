<?php
/**
 * Created by PhpStorm.
 * User: Alvaro
 * Date: 23/05/2017
 * Time: 20:04
 */

namespace AppBundle\Form;


use AppBundle\Entity\Bicicleta;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class BicicletaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('description')
            ->add('stock')
            ->add('price', IntegerType::class)
            ->add('imagen')
            ->add('imageFile', VichImageType::class, [
                'required'      => false,
                'allow_delete'  => true, // not mandatory, default is true
                'download_link' => true, // not mandatory, default is true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Bicicleta::class,
        ));
    }
}