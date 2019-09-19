<?php

namespace App\Form;

use App\Entity\Shortcut;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Url;

/**
 * Class ShortcutType
 *
 * @package App\Form
 * @author Bruno Buiret <bruno.buiret@gmail.com>
 */
class ShortcutType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title',
                TextType::class,
                [
                    'required'    => true,
                    'label'       => 'shortcuts.form.label_title',
                    'constraints' => [
                        new NotBlank(),
                        new Length([
                            'min' => 1,
                            'max' => 255,
                        ]),
                    ],
                ]
            )
            ->add(
                'subTitle',
                TextType::class,
                [
                    'required'    => true,
                    'label'       => 'shortcuts.form.label_sub_title',
                    'constraints' => [
                        new NotBlank(),
                        new Length([
                            'min' => 1,
                            'max' => 255,
                        ]),
                    ],
                ]
            )
            ->add(
                'color',
                ColorType::class,
                [
                    'required'    => true,
                    'label'       => 'shortcuts.form.label_color',
                    'constraints' => [
                        new NotBlank(),
                        new Length([
                            'min' => 1,
                            'max' => 7,
                        ]),
                    ],
                ]
            )
            ->add(
                'icon',
                TextType::class,
                [
                    'required'    => true,
                    'label'       => 'shortcuts.form.label_icon',
                    'constraints' => [
                        new NotBlank(),
                        new Length([
                            'min' => 1,
                            'max' => 100,
                        ]),
                    ],
                ]
            )
            ->add(
                'url',
                UrlType::class,
                [
                    'required'    => true,
                    'label'       => 'shortcuts.form.label_url',
                    'constraints' => [
                        new NotBlank(),
                        new Length([
                            'min' => 1,
                            'max' => 255,
                        ]),
                        new Url()
                    ],
                ]
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Shortcut::class,
        ]);
    }
}