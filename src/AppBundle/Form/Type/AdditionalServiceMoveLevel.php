<?php
namespace AppBundle\Form\Type;

use AppBundle\Entity\AdditionalServiceEntity;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class AdditionalServiceMoveLevel extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $choices = $options['choices'];
        $locale = $options['locale'];
        $parentId = $options['parentId'];
        
        $builder->add('parent', EntityType::class, array(
            'label'=> 'Перемістити до: ',
            'class' => $options['data_class'],
            'attr' => ['size' => 17, 'autocomplete'=>'off'],
            'choices' => $choices,

            'choice_label' => function ($treeElement)use ($locale) {
                $return = $treeElement->getTitleI18n($locale); 
                
                return $return; 
            }
            ,
            'choice_attr' => function ($treeElement)use ($parentId) {
                $return = ['style' => 'padding-left: ' . ($treeElement->getLvl() * 15) . 'px;']; 
                if ($treeElement->getID() == $parentId) {
                    $return['class'] = 'admin_select_current'; 
                }
                
                return $return; 
            }
        ));

        $builder->add('save', SubmitType::class, ['label' => 'зберегти']);

    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(['data_class' => AdditionalServiceEntity::class, 'choices'=>null, 'locale'=>null, 'parentId'=>null ]);
    }
}
?>