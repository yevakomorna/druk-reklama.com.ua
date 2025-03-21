<?php
namespace AppBundle\Form\Type;

use AppBundle\Entity\BlockImageEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class BlockImageType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('file_name', HiddenType::class, array());

        $builder->add('fileNameAssert', FileType::class, array('label' => 'image'));
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array('data_class' => BlockImageEntity::class, ));
    }

}
?>