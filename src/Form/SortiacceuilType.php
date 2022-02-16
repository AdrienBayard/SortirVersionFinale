<?php

namespace App\Form;

use App\Entity\Site;
use App\Entity\Sortie;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortiacceuilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        //SITE de l'SORTIE
        $builder->add('nom', EntityType::class, [
            'class' => Site::class,
            //Requete avec le query builder
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('c')
                    ->orderBy('c.nom', 'ASC');
            },
            'choice_label' => 'nom',
            'label' => 'Site', 'placeholder' => 'Tous', 'required' => false
        ]);
        // Bar de recherche
        $builder->add('search', SearchType::class, [
            'label' => 'Le nom de la sortie contient : ', 'mapped'=>false, 'attr' => ['class' => 'animated-search-form', 'placeholder' => 'Rechercher...'], 'required' => false

        ]);

        //Date de début Sortie
        $builder->add('minDate', DateType::class, [
            'label' => 'Entre',
            'mapped'=>false,
            'widget' => 'single_text',
            'required' => false
        ]);
        //Date de fin de l'Sortie
        $builder->add('maxDate', DateType::class, [
            'label' => 'et',
            'mapped'=>false,
            'widget' => 'single_text',
            'required' => false
        ]);
        // Les cases a cocher
        $builder->add('organiser', CheckboxType::class, [
            'mapped'=>false,
            'label' => 'Sorties dont je suis l\'organisateur'
            , 'required' => false
        ]);
        $builder->add('isAEteInscrit', CheckboxType::class, [
            'label' => 'Sorties  auxquelles je suis inscrit',
            'mapped'=>false
            , 'required' => false
        ]);
        $builder->add('isNotAEteInscrit', CheckboxType::class, [
            'label' => 'Sorties auxquelles je ne suis pas inscrit'
            ,'mapped'=>false
            , 'required' => false
        ]);
        $builder->add('archived', CheckboxType::class, [
            'label' => 'Sorties passées'
            ,'mapped'=>false
            , 'required' => false
        ]);

        // $builder->add('submit', SubmitType::class, ['label' => 'Rechercher', 'attr' => ['class' => 'hollow button secondary']]);
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
            'method'=>'GET',
            'csrf_protection' => false

        ]);
    }
}
