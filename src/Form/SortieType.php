<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',null, ["label"=> "Nom de la sortie : "])
            ->add('dateHeureDebut',DateTimeType::class, ["label"=> "Date et heure de la sortie : "])
            ->add('dateLimiteInscription',DateTimeType::class, ["label"=> "Date limite d'inscription : "])
            ->add('nbInscriptionMax',null, ["label"=> "Nombre de places : "])
            ->add('duree', null, ["label"=> "Durée : "])
            ->add('infosSortie',TextareaType::class, ['attr'=> ['class'=>'textAreaFix'], "label"=> "Description et infos : "])

            ->add('isPublished', null, ["label"=> "Publier la sortie : "])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
