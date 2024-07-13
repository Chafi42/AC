<?php

namespace App\Form;

use App\Entity\Message;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class MessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextType::class, [
                'attr' => [
                    'class' => 'bg-gray-100 w-full p-2 rounded-lg text-center my-4',
                ],

            ])
            ->add('envoyer', SubmitType::class, [
                'label' => 'Envoyer',
                'attr' => [
                    'class' => 'bg-blue-500 text-white p-2
                     w-full rounded-lg',

                ],
            ]);
        // ->add('createdAt', null, [
        //     'widget' => 'single_text',
        // ])
        // ->add('updatedAt', null, [
        //     'widget' => 'single_text',
        // ])
        // ->add('deletedAt', null, [
        //     'widget' => 'single_text',
        // ])
        // ->add('receiver', EntityType::class, [
        //     'class' => User::class,
        //     'choice_label' => 'id',
        // ])
        // ->add('sender', EntityType::class, [
        //     'class' => User::class,
        //     'choice_label' => 'id',
        // ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
        ]);
    }
}
