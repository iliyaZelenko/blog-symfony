<?php

namespace App\Form;

use App\Form\DataObjects\User\UserCreationData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // по умолчанию походу TextType::class
            ->add('username')
            // type="email" для input добавилось, значит symfony определяет по "child" параметру что это Email,
            // не нужно писать EmailType::class вторым аргументов
            ->add('email')
            ->add('plainPassword', PasswordType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserCreationData::class,
        ]);
    }
}
