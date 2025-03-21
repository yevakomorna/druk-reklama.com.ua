<?php
namespace AppBundle\Form\Type;

use AppBundle\Entity\AdditionalServiceParamEntity;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class AdditionalServiceParamType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->add('name', TextType::class, ['label' => 'Назва', "attr" => ["readonly" => "readonly"]]);
        $builder->add('value', TextType::class, ['label' => 'Значення']);
        $builder->add('description', TextareaType::class, ['label' => 'Опис']);

        //$builder->add('save', SubmitType::class, ['label' => 'зберегти']);
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array('data_class' => AdditionalServiceParamEntity::class ));
    }
}
?>