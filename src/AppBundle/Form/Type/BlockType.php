<?php
namespace AppBundle\Form\Type;

use AppBundle\Entity\BlockEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class BlockType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('title', TextType::class, array('required' => false));
        $builder->add('description', TextareaType::class, array('required' => false));
        $builder->add('priority', TextType::class, array('required' => false));

        $builder->add('images', CollectionType::class, array(
            'entry_type' => BlockImageType::class,
            'entry_options' => array('label' => false, 'required' => false),
            ));

        $builder->add('save', SubmitType::class, ['label' => 'save']);
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array('data_class' => BlockEntity::class, ));
    }
}
?>