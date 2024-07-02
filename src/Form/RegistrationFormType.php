<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'rounded-lg block w-full p-2.5 mt-4', 
                    'placeholder' => 'name@company.com', 
                    'required' => true
                ],
                'label_attr' => [
                    'class' => 'mt-4',
                ],
            ])
            ->add('password', PasswordType::class, [
                'attr' => [
                    'class' => 'rounded-lg block w-full p-2.5 mt-4', 
                    'placeholder' => '********', 
                    'required' => true
                ],         
                'label' => 'Mot de passe',
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'max' => 4096,
                    ]),
                ],
                'label_attr' => [
                    'class' => 'mt-4',
                ],
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'class' => 'rounded-lg block w-full p-2.5 mt-4', 
                    'placeholder' => 'Joe', 
                    'required' => true
                ],
                'label_attr' => [
                    'class' => 'mt-4',
                ],
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'class' => 'rounded-lg block w-full p-2.5 mt-4', 
                    'placeholder' => 'Doe', 
                    'required' => true
                ],
                'label_attr' => [
                    'class' => 'mt-4',
                ],
            ])
            ->add('age', IntegerType::class, [
                'label' => 'Age',
                'attr' => [
                    'class' => 'rounded-lg block w-full p-2.5 mt-4', 
                    'placeholder' => '25', 
                    'required' => true
                ],
                'label_attr' => [
                    'class' => 'mt-4',
                ],
            ])
            ->add('birthDate', DateType::class, [
                'label' => 'Date de naissance',
                'attr' => [
                    'class' => 'rounded-lg block w-full p-2.5 mt-4', 
                    'placeholder' => '1996-12-25', 
                    'required' => true
                ],
                'label_attr' => [
                    'class' => 'mt-4',
                ],
                'format' => 'yyyy-MM-dd',
            ])
            ->add('phone', IntegerType::class, [
                'label' => 'Téléphone',
                'attr' => [
                    'class' => 'rounded-lg block w-full p-2.5 mt-4', 
                    'placeholder' => '123456789', 
                    'required' => true
                ],
                'label_attr' => [
                    'class' => 'mt-4',
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'attr' => [
                    'class' => 'text-center rounded-lg ms-4', 
                    'required' => true
                ],
                'label' => "J'ai lu et j'accepte la cgu.",
                'label_attr' => [
                    'class' => 'mt-4',
                    'style' => 'margin: 10px; padding: 5px;'
                ],
                'row_attr' => [
                    'class' => 'mt-4'
                ],
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter nos conditions.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}