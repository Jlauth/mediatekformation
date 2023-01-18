<?php

namespace App\Form;

use App\Entity\Playlist;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PlaylistType.
 *
 * @author Jean
 */
class PlaylistTypeAdd extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
                ->add('name', TextType::class, [
                    'label' => 'Intitulé de la playlist',
                    'required' => true,
                ])
                ->add('description', TextareaType::class, [
                    'label' => 'Description de la nouvelle playlist',
                    'required' => false,
                ])
                ->add('submit', SubmitType::class, [
                    'label' => 'Ajouter',
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Playlist::class,
        ]);
    }
}
