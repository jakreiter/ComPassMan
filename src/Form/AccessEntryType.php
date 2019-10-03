<?php

namespace App\Form;

use App\Entity\AccessEntry;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccessEntryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category')
            ->add('title')
            ->add('userName')
            ->add('decryptedPassword', TextType::class, [ 'label' => 'Password', 'required'=>false])
            ->add('decryptedKey', TextareaType::class, [ 'label' => 'Key', 'required'=>false])
            ->add('url')
            ->add('notes')

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AccessEntry::class,
        ]);
    }
}
