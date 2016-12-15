<?php

namespace IgorGoroun\FTNWBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class SettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, ['label'=>'Имя Фамилия (LAT)','attr'=>['class'=>'form-control'],'label_attr'=>['class'=>'col-xs-12 col-sm-4 control-label']])
            ->add('origin', TextType::class, ['label'=>'Ориджн','attr'=>['class'=>'form-control'],'label_attr'=>['class'=>'col-xs-12 col-sm-4 control-label']])
            ->add('subscription', TextareaType::class, ['label'=>'Подпись','attr'=>['class'=>'form-control message_viewer','rows'=>4,'style'=>''],'label_attr'=>['class'=>'col-xs-12 col-sm-4 control-label']])
            ->add('aslist_netmail', CheckboxType::class, ['label'=>'Открывать нетмейл списком','required'=>false])
            ->add('aslist_echomail', CheckboxType::class, ['label'=>'Открывать эхоконференции списком','required'=>false])
            ->add('save', SubmitType::class, ['label'=>'Save profile','attr'=>['class'=>'btn btn-md btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'IgorGoroun\FTNWBundle\Entity\Point'
        ));
    }

    public function getName()
    {
        return 'user_bundle_registration_type';
    }
}
