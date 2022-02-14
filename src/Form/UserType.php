<?php

namespace App\Form;

use App\Entity\Site;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use mysql_xdevapi\BaseResult;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Vich\UploaderBundle\Form\Type\VichFileType;


class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('pseudo',null, ["label" => "Pseudo : "])
            ->add('prenom',null, ["label" => "Prénom : "])
            ->add('nom',null, ["label" => "Nom : "])
            ->add('telephone',null, ["label" => "Téléphone: "])
            ->add('mail',null, ["label" => "Email : "])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe :'],
                'second_options' => ['label' => 'Confirmation :'],
            ])
            ->add('site',EntityType::class, ['label' => 'Site de rattachement :', "class"=>Site::class,
                "choice_label" => "nom",])

            ->add('fichierImage',
                VichFileType::class,
                ["label" => "Ajouter une image",
                    'required' => false,
                    'allow_delete' => false,
                    'download_uri' => false,

                ]
            )



        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
