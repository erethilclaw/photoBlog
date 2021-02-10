<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class ArticleFromType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('position')
            ->add('slug')
            ->add('imageFile', FileType::class, [
                'mapped' => false,
                'required' => false,
                'constraints' => new Image([
                    'maxSize' => '5M'
                ])
            ])
            ->add('titleEn')
            ->add('titleEs')
            ->add('titleCa')
            ->add('contentEn')
            ->add('contentEs')
            ->add('contentCa')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
