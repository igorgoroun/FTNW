<?php

namespace IgorGoroun\FTNWBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MessagePostType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('hTo',TextType::class,['label'=>'To:','attr'=>['class'=>'form-control','readonly'=>'readonly'],'label_attr'=>['class'=>'col-sm-2 control-label']])
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
            'data_class' => 'IgorGoroun\FTNWBundle\Entity\MessageBatch'
        ));
    }
}
