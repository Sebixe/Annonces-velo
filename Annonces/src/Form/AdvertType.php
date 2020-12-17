<?php

namespace App\Form;

use App\Entity\Advert;
use App\Entity\Category;
use App\Entity\Tag;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdvertType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('title')
            ->add('price')
            ->add('description')
            ->add('fork')
            ->add('year', DateType::class, ['years' => self::getYears()])
            ->add('category', EntityType::class, ['class' => Category::class])
            ->add('tags', EntityType::class, ['class' => Tag::class, 'multiple' => true, 'expanded' => true, 'choice_label' => 'name'])
            ->add('gallery', PhotoGalleryType::class)
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Advert::class,
        ]);
    }

    private static function getYears(): array
    {
        $years = [];
        $currentYear = date('Y');
        $numberOfYears = 20;
        for($i=0; $i < $numberOfYears; $i++) {
            $years[] = $currentYear - $i;
        }

        return $years;
    }
}
