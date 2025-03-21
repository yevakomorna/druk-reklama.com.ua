<?php
namespace AppBundle\Form\Type;

use AppBundle\Entity\SectionEntity;
use AppBundle\Entity\AdditionalServiceEntity;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class SectionType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->add('name', TextType::class, ['label' => 'ID розділу']);
        $builder->add('titleUa', TextType::class, ['label' => 'Назва (укр)']);
        $builder->add('template', ChoiceType::class, ['label' => 'Шаблон', 'choices' => ['simple_text_page' => 'simple_text_page', 'home_page' => 'home_page', 'contact_page'=>'contact_page', 'order_page'=>'order_page', 'order_confirm' => 'order_confirm' ], ]);
        $builder->add('status', ChoiceType::class, ['label' => 'Показувати в меню', 'choices' => ['не показуати' => 0, 'показуати' => 1, ], ]);
        
        $locale = $options['locale'];
        $builder->add('additionalService', EntityType::class, array(
            'label'=>'Додаткові послуги: ',
            'class' => AdditionalServiceEntity::class,
            'choices' => $options['additionalServices'],
            'required' => false,
            'choice_label' => function ($service)use ($locale) {
                $return = $service->getTitleI18n($locale); 
                
                return $return; 
            }
        ));
        
        $builder->add('tapeWidth', TextType::class, ['label' => 'Макс. ширина рулону мм (для друку)', 'required' => false,]);


    $builder->add('areaEdge', TextType::class, ['label' => 'Гранична площа при якій зменшується ціна друку м.кв.', 'required' => false,]);
    $builder->add('areaPriceMin', TextType::class, ['label' => 'Ціна до граничної площі, грн/м.кв (0 - ціна розраховується індивідуально)', 'required' => false,]);
    $builder->add('areaPriceMax', TextType::class, ['label' => 'Ціна від граничної, грн/м.кв', 'required' => false,]);
    $builder->add('areaPriceVolume1000', TextType::class, ['label' => 'Ціна при обємі друку в місяць від 1000 до 5000 грн, грн/м.кв', 'required' => false,]);
    $builder->add('areaPriceVolume5000', TextType::class, ['label' => 'Ціна при обємі друку в місяць від 5000 грн, грн/м.кв', 'required' => false,]);
    $builder->add('areaPriceVolumeSingle5000', TextType::class, ['label' => 'Ціна при одноразовому замовленні від 5000 грн, грн/м.кв', 'required' => false,]);


        

        $builder->add('save', SubmitType::class, ['label' => 'зберегти']);

    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array('data_class' => SectionEntity::class, 'additionalServices'=> null, 'locale'=> null ));
    }
}
?>