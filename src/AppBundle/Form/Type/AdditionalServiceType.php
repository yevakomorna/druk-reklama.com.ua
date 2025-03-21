<?php
namespace AppBundle\Form\Type;

use AppBundle\Entity\AdditionalServiceEntity;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


class AdditionalServiceType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->add('titleUa', TextType::class, ['label' => 'Назва (укр)']);
        $builder->add('status', ChoiceType::class, ['label' => 'Показувати в меню', 'choices' => ['не показуати' => 0, 'показуати' => 1, ], ]);
        
        $builder->add('params', CollectionType::class, array(
            'entry_type' => AdditionalServiceParamType::class,
            'entry_options' => array('label' => false),
        ));

        $builder->add('save', SubmitType::class, ['label' => 'зберегти']);

    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array('data_class' => null ));
    }
}
?>