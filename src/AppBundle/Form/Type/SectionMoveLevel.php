<?php
namespace AppBundle\Form\Type;

use AppBundle\Entity\SectionEntity;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class SectionMoveLevel extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $sections = $options['sections'];
        $locale = $options['locale'];
        $sectionParentId = $options['sectionParentId'];
        $builder->add('parent', EntityType::class, array(
            'label'=>'Перемістити до: ',
            'class' => SectionEntity::class,
            'attr' => ['size' => 17, 'autocomplete'=>'off'],
            'choices' => $sections,

            'choice_label' => function ($section)use ($locale) {
                $return = $section->getTitleI18n($locale); 
                
                return $return; 
            }
            ,
            'choice_attr' => function ($section)use ($sectionParentId) {
                $return = ['style' => 'padding-left: ' . ($section->getLvl() * 15) . 'px;']; 
                if ($section->getID() == $sectionParentId) {
                    $return['class'] = 'admin_select_current'; 
                }
                
                return $return; 
            }
        ));

        $builder->add('save', SubmitType::class, ['label' => 'зберегти']);

    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(['data_class' => SectionEntity::class, 'sections'=>null, 'locale'=>null, 'sectionParentId'=>null ]);
    }
}
?>