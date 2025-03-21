<?php
namespace AppBundle\Form\Type;

use AppBundle\Entity\SectionEntity;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;

/**
 * редагування тільки текстів відповідної категорії
*/
class SectionDescType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->add('descriptionUa', CKEditorType::class, ['label' => 'Опис (укр)', 'attr' => ['class' => 'tinymce'], 'config' => ['contentsCss'=>'/css/CKEditorContentCustom.css', 'allowedContent'=>true , 'filebrowserBrowseRoute' => 'elfinder', 'filebrowserBrowseRouteParameters' => ['instance' => 'default', 'homeFolder' => 'uploads']], ]);
        $builder->add('descriptionTwoUa', CKEditorType::class, ['label' => 'Додатковий опис (укр) (на сторінці оформлення замовлення)', 'attr' => ['class' => 'tinymce'], 'config' => ['contentsCss'=>'/css/CKEditorContentCustom.css', 'allowedContent'=>true , 'filebrowserBrowseRoute' => 'elfinder', 'filebrowserBrowseRouteParameters' => ['instance' => 'default', 'homeFolder' => 'uploads']], ]);        
        $builder->add('save', SubmitType::class, ['label' => 'зберегти']);


    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array('data_class' => SectionEntity::class, ));
    }
}
?>