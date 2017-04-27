<?php
/**
 * Created by PhpStorm.
 * User: Alvaro
 * Date: 26/04/2017
 * Time: 17:45
 */

namespace AppBundle\Form;


use AppBundle\Entity\Comentario;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ComentarioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('titulo', TextType::class)
            ->add('contenido')
            ->add('Comentar', SubmitType::class)

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'Comentario' => Comentario::class,
        ));
    }
}