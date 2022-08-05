<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
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
            ->add('username', TextType::class, [
                'label' => 'Nom d\'utilisateur',
                'label_attr' => array('class' => 'label'),
                'attr' => [
                    'class' => 'input',
                    'placeholder' => 'John Doe'
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'mapped' => false,
                'label' => 'Mot de passe',
                'type' => PasswordType::class,
                'attr' => [
                    'class' => 'input',
                    'placeholder' => '********'
                ],
                'label_attr' => array('class' => 'label'),
                'first_name' => 'password',
                'second_name' => 'confirm',
                'first_options' => [
                    'label' => 'Mot de passe',
                    'label_attr' => array('class' => 'label'),
                    'attr' => [
                        'class' => 'input',
                        'placeholder' => '********'
                    ],
                ],
                'second_options' => [
                    'label' => 'Confirmation',
                    'label_attr' => array('class' => 'label'),
                    'attr' => [
                        'class' => 'input',
                        'placeholder' => '********'
                    ],
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                        'max' => 4096,
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
