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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class OrderConfirmType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('name', TextType::class, ['label' => 'Ім\'я', 'required' => true, "attr" => ["oninput"=>"setCustomValidity('')", "oninvalid"=>"this.setCustomValidity('Обов\'язкове поле')" ,"placeholder" => "Як до Вас звертатись?"]]);
        $builder->add('paymentMethod', ChoiceType::class, ["attr" => ["autocomplete"=>"off" , "oninput"=>"setCustomValidity('')", "oninvalid"=>"this.setCustomValidity('Обов\'язкове поле')"], 'label' => 'Спосіб оплати *', 'required' => true, 'choices' => ['' => '', 'Оплата на розрахунковий рахунок (з ПДВ)' => "checking_account", "Оплата ФОП 3 група 5% (без ПДВ)" => "FOP"]]);

        $builder->add('city', TextType::class, ['label' => 'Населений пункт', 'required' => false]);
        $builder->add('department', TextType::class, ['label' => '№ відділення', 'required' => false]);

        $builder->add('selfCheckout', TextType::class, ['label' => 'Самовивіз', 'required' => false, "attr" => ["readonly" => "readonly", "class" => "readonly_blue"]]);
        $builder->add('phone', TextType::class, ['label' => 'Телефон', 'required' => true, "attr" => ["placeholder" => "Як з Вами звязатись?"]]);
        $builder->add('email', TextType::class, ['label' => 'email', 'required' => false]);

        $builder->add('comment', TextareaType::class, ['label' => 'Коментар до замовлення', 'required' => false, "attr" => ["cols" => "80", "rows" => "6"]]);

        $builder->add('costOfPrinting', TextType::class, ['label' => 'Вартість друку', 'required' => false]);
        $builder->add('costOfAdditionalServices', TextType::class, ['label' => 'Вартість додаткових послуг', 'required' => false]);
        $builder->add('totalPayment', TextType::class, ['label' => 'Всього до оплати', 'required' => false]);

        $builder->add('save', SubmitType::class, ['label' => 'Підтвердити', "attr" => ["class" => "gray_button_input"], ]);
        
    }

    public function configureOptions(OptionsResolver $resolver) {
        //$resolver->setDefaults(array('data_class' => BlockEntity::class, ));
    }
}
?>