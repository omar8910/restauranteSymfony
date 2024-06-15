<?php

namespace App\Form;

use App\Entity\Pedidos;
use App\Entity\Productos;
use App\Entity\Restaurantes;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PedidosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fecha', null, [
                'widget' => 'single_text',
            ])
            ->add('enviado')
            ->add('restaurante', EntityType::class, [
                'class' => Restaurantes::class,
                'choice_label' => 'id',
            ])
            ->add('productos', EntityType::class, [
                'class' => Productos::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pedidos::class,
        ]);
    }
}
