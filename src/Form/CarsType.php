<?php

namespace App\Form;
use App\Entity\Cars;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class CarsType extends AbstractType {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('brand', null, ['attr' => ['class' => 'form-control']])
                ->add('model', null, ['attr' => ['class' => 'form-control']])
                ->add('year', IntegerType::class, ['attr' => ['class' => 'form-control']])
                ->add('mileage', IntegerType::class, ['attr' => ['class' => 'form-control']])
                ->add('fuel', null, ['attr' => ['class' => 'form-control']])
                ->add('type', null, ['attr' => ['class' => 'form-control']])
                ->add('gearBox', null, ['attr' => ['class' => 'form-control']])
                ->add('price', IntegerType::class, ['attr' => ['class' => 'form-control']])
                ->add('description', null, ['attr' => ['class' => 'form-control']])
                ->add('picture', FileType::class, [
                    'label' => false,
                    'required' => false,
                    'mapped' => false,
                    'attr' => ['class' => 'form-control-file'],
                    'constraints' => [
                        new File([
                            'maxSize' => '4500k',
                            'mimeTypesMessage' => 'Veuillez télécharger un fichier valide !',
                        ])
                    ],
                ])
            ;
        }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cars::class,
        ]);
    }
}

