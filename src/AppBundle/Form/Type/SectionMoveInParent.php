<?php
namespace AppBundle\Form\Type;

use AppBundle\Entity\SectionEntity;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class SectionMoveInParent extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $sections = $options['sections'];
        $locale = $options['locale'];
        $sectionId = $options['sectionId'];
        $builder->add('direction', ChoiceType::class, ['label' => 'Розмістити', 'choices' => ['перед' => 'Prev', 'після' => 'Next', ], ]);
        
        $builder->add('sibling', EntityType::class, array(
                'label'=> false,
                'class' => SectionEntity::class,
                'attr' => ['size' => 15, 'autocomplete'=>'off'],
                'choices' => $sections,
    
                'choice_label' => function ($section)use ($locale) {
                    $return = $section->getTitleI18n($locale); 
                    
                    return $return; 
                },
                'choice_attr' => function ($section)use ($sectionId) {
                     
                    return ($section->getID() == $sectionId) ? ['selected' => 'selected'] : [];
                }
            )
        );

        $builder->add('save', SubmitType::class, ['label' => 'зберегти']);

    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([ 'sections'=>null, 'locale'=>null, 'sectionId'=>null]);
    }
}
?>