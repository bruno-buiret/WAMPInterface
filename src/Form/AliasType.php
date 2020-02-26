<?php

namespace App\Form;

use App\Entity\Alias;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class AliasType
 *
 * @package App\Form
 * @author Bruno Buiret <bruno.buiret@gmail.com>
 */
class AliasType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'required'    => true,
                    'constraints' => [
                        new NotBlank(),
                        new Length([
                            'min' => 1,
                            'max' => 255,
                        ]),
                    ],
                    'label'       => 'aliases.form.label_name',
                ]
            )
            ->add(
                'description',
                TextareaType::class,
                [
                    'required' => false,
                    'label'    => 'aliases.form.label_description',
                ]
            )
            ->add(
                'url',
                TextType::class,
                [
                    'required'    => true,
                    'constraints' => [
                        new NotBlank(),
                        new Length([
                            'min' => 1,
                            'max' => 255,
                        ]),
                    ],
                    'label'       => 'aliases.form.label_url',
                ]
            )
            ->add(
                'path',
                TextType::class,
                [
                    'required'    => true,
                    'constraints' => [
                        new NotBlank(),
                    ],
                    'label'       => 'aliases.form.label_path',
                ]
            )
            ->add(
                'hidden',
                ChoiceType::class,
                [
                    'required' => true,
                    'choices'  => [
                        'aliases.form.label_no'  => false,
                        'aliases.form.label_yes' => true,
                    ],
                    'label'    => 'aliases.form.label_hidden',
                ]
            )
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Alias::class,
        ]);
    }
}
