<?php

namespace App\Form;

use App\Entity\PortofolioPage;
use App\Transformer\ImageToPageTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;


class PortofolioPageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('natureGallery', FileType::class, [
                'multiple' => true,
                'label' => 'form.natureGallery',
                'mapped' => false,
                'required' => false,
              /*  'constraints' => [
                    new Image([
                        'maxSize' => '5M',
                    ])
                ],*/
            ])
            ->add('eventGallery', FileType::class, [
                'multiple' => true,
                'label' => 'form.eventGallery',
                'mapped' => false,
                'required' => false
            ])
            ->add('sesionGallery', FileType::class, [
                'multiple' => true,
                'label' => 'form.sesionGallery',
                'mapped' => false,
                'required' => false
            ])
            ->add('showGallery', FileType::class, [
                'multiple' => true,
                'label' => 'form.showGallery',
                'mapped' => false,
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PortofolioPage::class
        ]);
    }
}
