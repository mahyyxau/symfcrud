<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class DatalistType extends AbstractType {

    public function configureOptions(OptionsResolver $resolver) {

        $resolver->setDefaults([
            'data_class' => \Doctrine\ORM\Mapping\Entity::class,
        ]);
    }

    public function getParent() {
        return EntityType::class;
    }
}								
		