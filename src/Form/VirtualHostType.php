<?php

namespace App\Form;

use App\Entity\VirtualHost;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

/**
 * Class VirtualHostType
 *
 * @package App\Form
 * @author Bruno Buiret <bruno.buiret@gmail.com>
 */
class VirtualHostType extends AbstractType
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
                    'label'       => 'virtual_hosts.form.label_name',
                ]
            )
            ->add(
                'description',
                TextareaType::class,
                [
                    'required' => false,
                    'label'    => 'virtual_hosts.form.label_description',
                ]
            )
            ->add(
                'host',
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
                    'label'       => 'virtual_hosts.form.label_host',
                ]
            )
            ->add(
                'port',
                IntegerType::class,
                [
                    'required'    => true,
                    'constraints' => [
                        new NotBlank(),
                        new Range([
                            'min' => 1,
                            'max' => 65535,
                        ]),
                    ],
                    'label'       => 'virtual_hosts.form.label_port',
                ]
            )
            ->add(
                'ssl',
                ChoiceType::class,
                [
                    'required' => true,
                    'choices'  => [
                        'virtual_hosts.form.label_no'  => false,
                        'virtual_hosts.form.label_yes' => true,
                    ],
                    'label'    => 'virtual_hosts.form.label_ssl',
                ]
            )
            ->add(
                'hidden',
                ChoiceType::class,
                [
                    'required' => true,
                    'choices'  => [
                        'virtual_hosts.form.label_no'  => false,
                        'virtual_hosts.form.label_yes' => true,
                    ],
                    'label'    => 'virtual_hosts.form.label_hidden',
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
            'data_class' => VirtualHost::class,
        ]);
    }
}
