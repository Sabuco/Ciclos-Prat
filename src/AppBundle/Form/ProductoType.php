<?php
/**
 * Created by PhpStorm.
 * User: Alvaro
 * Date: 14/03/2017
 * Time: 21:04
 */

namespace AppBundle\Form;


use AppBundle\Entity\Producto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('description')
            ->add('stock')
            ->add('price', IntegerType::class)
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Producto::class,
        ));
    }
}