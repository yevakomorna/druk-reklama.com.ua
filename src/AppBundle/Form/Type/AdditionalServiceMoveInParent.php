<?php
namespace AppBundle\Form\Type;

use AppBundle\Entity\AdditionalServiceEntity;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class AdditionalServiceMoveInParent extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $choices = $options['choices'];
        $locale = $options['locale'];
        $currentId = $options['currentId'];
        $builder->add('direction', ChoiceType::class, ['label' => 'Розмістити', 'choices' => ['перед' => 'Prev', 'після' => 'Next', ], ]);
        
        $builder->add('sibling', EntityType::class, array(
                'label'=> false,
                'class' => $options['entityTypeClass'],
                'attr' => ['size' => 15, 'autocomplete'=>'off'],
                'choices' => $choices,
    
                'choice_label' => function ($treeElement)use ($locale) {
                    $return = $treeElement->getTitleI18n($locale); 
                    
                    return $return; 
                },
                'choice_attr' => function ($treeElement)use ($currentId) {
                     
                    return ($treeElement->getID() == $currentId) ? ['selected' => 'selected'] : [];
                }
            )
        );

        $builder->add('save', SubmitType::class, ['label' => 'зберегти']);

    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(['choices'=>null, 'locale'=>null, 'currentId'=>null, 'entityTypeClass'=> null]);
    }
}
?>