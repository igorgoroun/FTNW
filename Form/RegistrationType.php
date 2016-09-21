<?php

namespace IgorGoroun\FTNWBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            /*->add('email', EmailType::class)*/
            ->add('username', TextType::class, ['label'=>'Имя Фамилия (LAT)','attr'=>['class'=>'form-control'],'label_attr'=>['class'=>'col-xs-12 col-sm-5 control-label']])
            ->add('classic', CheckboxType::class, ['label'=>'Классический поинт без веб-доступа','required'=>false])
            ->add('plainPassword', RepeatedType::class, array(
                'options'=>array(
                    'attr'=>['class'=>'form-control'],
                    'label_attr'=>['class'=>'col-xs-12 col-sm-5 control-label'],
                ),
                'type' => PasswordType::class,
                'first_options' => array('label' => 'Пароль'),
                'second_options' => array('label' => 'Пароль еще раз'),
            ))
            ->add('save', SubmitType::class, ['label'=>'Получить','attr'=>['class'=>'btn btn-md btn-primary']]);
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
