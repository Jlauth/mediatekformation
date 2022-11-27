<?php

namespace App\Form;

use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Description of CategorieType
 *
 * @author Jean
 */
class CategorieType extends AbstractType {
    
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder
                ->add('name', TextType::class, [
                    'label' => 'Intitulé de la catégorie',
                    'required' => true,
                ])
                ->add('submit', SubmitType::class, [
                    'label' => 'Ajouter'
                ])
        ;   
    }
    
     public function configureOptions(OptionsResolver $resolver): void{
        $resolver->setDefaults([
            'data_class' => Categorie::class,
        ]);
    }

}
