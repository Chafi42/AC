<?php

namespace App\Form;
use App\Entity\Cars;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class CarsType extends AbstractType {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('brand', null, ['attr' => ['class' => 'form-control', 'required' => 'true']])
                ->add('model', null, ['attr' => ['class' => 'form-control', 'required' => 'true']])
                ->add('year', IntegerType::class, ['attr' => ['class' => 'form-control', 'required' => 'true']])
                ->add('mileage', IntegerType::class, ['attr' => ['class' => 'form-control', 'required' => 'true']])
                ->add('fuel', null, ['attr' => ['class' => 'form-control', 'required' => 'true']])
                ->add('type', null, ['attr' => ['class' => 'form-control', 'required' => 'true']])
                ->add('gearBox', null, ['attr' => ['class' => 'form-control', 'required' => 'true']])
                ->add('price', IntegerType::class, ['attr' => ['class' => 'form-control', 'required' => 'true']])
                ->add('description', TextareaType::class, ['attr' => ['class' => 'form-control' , 'required' => 'true']])
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

