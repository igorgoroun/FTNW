<?php

namespace IgorGoroun\FTNWBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NetmailPostType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('hTo',TextType::class,['label'=>'To:','attr'=>['class'=>'form-control','placeholder'=>'Recipient Name'],'label_attr'=>['class'=>'col-xs-12 col-sm-2 control-label']])
            ->add('hToFtn',TextType::class,['attr'=>['class'=>'form-control','placeholder'=>'z:nnn/ff.pp']])
            ->add('subject',TextType::class,['label'=>'Subject:','attr'=>['placeholder'=>'Subject','class'=>'form-control'],'label_attr'=>['class'=>'col-sm-2 control-label']])
            ->add('body',TextareaType::class,['attr'=>['rows'=>14,'class'=>'form-control textarea ftnweditor']])
            ->add('save',SubmitType::class,['label'=>'Send','attr'=>['value'=>'Send','class'=>'btn btn-primary btn-md']])
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'IgorGoroun\FTNWBundle\Entity\Netmail'
        ));
    }
}
