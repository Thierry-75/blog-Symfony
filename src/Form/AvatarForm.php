<?php

namespace App\Form;

use App\Entity\Avatar;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AvatarForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo',TextType::class,['attr'=>['class'=>'input-gray','placeholder'=>'Votre pseudo','required'=>true,'autofocus'=>false, 'accept'=>'image/png'],
            'label'=>'Pseudonyme',
            'label_attr'=>['class'=>'mb-3 block text-sm text-cyan-600 font-medium ']
            ])

            ->add('image',FileType::class,['label'=>false,'multiple'=>false,'mapped'=>false])

            ->add('submit',SubmitType::class,['attr'=>['class'=>'btn-confirmation mb-10'],
            'label'=>'Saisir'
            ])

            ->addEventListener(FormEvents::POST_SUBMIT, $this->addDate(...))
            
        ;
    }

    public function addDate(PostSubmitEvent $event)
    {
        $data = $event->getData();
        if (!($data instanceof Avatar)) return;
        $data->setCreatedAt(new \DateTimeImmutable());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Avatar::class,
        ]);
    }
}
