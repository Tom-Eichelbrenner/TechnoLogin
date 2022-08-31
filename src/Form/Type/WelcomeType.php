<?php

namespace App\Form\Type;

use App\Model\WelcomeModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WelcomeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('siteName', TextType::class, [
                'label' => WelcomeModel::SITE_TITLE_LABEL,
                'label_attr' => array('class' => 'label'),
                'attr' => [
                    'class' => 'input',
                    'placeholder' => WelcomeModel::SITE_TITLE_PLACEHOLDER
                ],
            ])
            ->add('username', TextType::class, [
                'label' => WelcomeModel::USERNAME_LABEL,
                'label_attr' => array('class' => 'label'),
                'attr' => [
                    'class' => 'input',
                    'placeholder' => WelcomeModel::USERNAME_PLACEHOLDER
                ],
            ])
            ->add('password', PasswordType::class,[
                'label' => WelcomeModel::PASSWORD_LABEL,
                'label_attr' => array('class' => 'label'),
                'attr' => [
                    'class' => 'input',
                    'placeholder' => WelcomeModel::PASSWORD_PLACEHOLDER
                ],
            ])
        ->add('submit', SubmitType::class,[
            'label' => 'Installer',
            'attr' => [
                'class' => 'button is-button'
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => WelcomeModel::class,
            'csrf_token_id' => 'welcome',
        ]);
    }
}