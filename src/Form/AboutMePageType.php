<?php

namespace App\Form;

use App\Entity\AboutMePage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AboutMePageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('text_es', null, ['label' => 'form.text_es'])
            ->add('text_ca', null, ['label' => 'form.text_ca'])
            ->add('text_en', null, ['label' => 'form.text_en'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AboutMePage::class,
        ]);
    }
}
