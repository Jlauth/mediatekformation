<?php

namespace App\Form;

use App\Entity\Formation;
use App\Entity\Playlist;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Description of PlaylistType
 *
 * @author Jean
 */
class PlaylistType extends AbstractType {
    
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        
        $builder
            ->add('name', TextType::class, [
                'label' => 'Intitulé de la playlist',
                'required' => true
            ])
            ->add('formations', EntityType::class, [
                'class' => Formation::class,
                'label' => 'Titre de la formation',
                'choice_label' => 'title',
                'multiple' => true,
                'required' => false
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description de la nouvelle playlist',
                'required' => false
            ])
                ->add('submit', SubmitType::class, [
                'label' => 'Ajouter'
            ])
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => Playlist::class,
        ]);
    }
    
}