<?php

namespace App\Form;

use App\Entity\Sortie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnnulerSortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',null, ["label"=> "Nom de la sortie : "])
            ->add('dateHeureDebut',DateTimeType::class, ["label"=> "Date et heure de la sortie : "])
         /*   ->add('dateLimiteInscription',DateTimeType::class, ["label"=> "Date limite d'inscription : "])
            ->add('nbInscriptionMax',null, ["label"=> "Nombre de places : "])*/
         /*   ->add('duree', null, ["label"=> "Durée : "])
            ->add('infosSortie',null, ["label"=> "Description et infos : "])

            ->add('isPublished', null, ["label"=> "Publier la sortie : "])*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
