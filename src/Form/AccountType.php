<?php

namespace App\Form;

use App\Entity\Account;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class,[
                'label' => 'Firstname',
                'attr' =>[
                    'placeholder' => 'Enter your firstname'   
                ]
            ])
            ->add('lastname', TextType::class,[
                'label' => 'Lastname',
                'attr' =>[
                    'placeholder' => 'Enter your lastname'   
                ]
            ])
            ->add('email', EmailType::class,[
                'label' => 'Email',
                'attr' =>[
                    'placeholder' => 'Enter your email'   
                ]
            ])
            ->add('password', RepeatedType::class,[
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'Password',
                    'attr' =>[
                        'placeholder' => 'Enter your password',
                        'hash_property_path' => 'password'   
                    ]
                ],
                'second_options' => [
                    'label' => 'Password confirmation',
                    'attr' =>[
                        'placeholder' => 'Confirm the password'   
                    ]
                ],
                'mapped' => false
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Submit',
                'attr' => [
                    'class' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Account::class,
        ]);
    }
}
