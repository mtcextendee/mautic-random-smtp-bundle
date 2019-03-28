<?php


/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\MauticRandomSmtpBundle\Integration;

use Mautic\PluginBundle\Integration\AbstractIntegration;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints as Assert;

class RandomSmtpIntegration extends AbstractIntegration
{

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getName()
    {
        return 'RandomSmtp';
    }

    public function getIcon()
    {
        return 'plugins/MauticRandomSmtpBundle/Assets/img/icon.png';
    }

    /**
     * @return array
     */
    public function getFormSettings()
    {
        return [
            'requires_callback'      => false,
            'requires_authorization' => false,
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getAuthenticationType()
    {
        return 'none';
    }


    /**
     * @param \Mautic\PluginBundle\Integration\Form|FormBuilder $builder
     * @param array                                             $data
     * @param string                                            $formArea
     */
    public function appendToForm(&$builder, $data, $formArea)
    {
        if ($formArea == 'features') {
            $builder->add(
                'smtps',
                TextareaType::class,
                [
                    'label'      => 'mautic.random.smtp.smtps',
                    'label_attr' => ['class' => 'control-label'],
                    'attr'       => [
                        'class'        => 'form-control',
                        'tooltip'        => 'mautic.random.smtp.smtps.tooltip',
                        'rows' => 10
                    ],
                    'constraints' => [
                        new Assert\NotBlank(
                            [
                                'message' => 'mautic.core.required',
                            ]
                        ),
                    ],
                ]
            );

            $builder->add(
                'host',
                NumberType::class,
                [
                    'label'      => 'mautic.random.smtp.column.host',
                    'label_attr' => ['class' => 'control-label'],
                    'attr'       => [
                        'class'  => 'form-control',
                        'tooltip' => 'mautic.random.smtp.column.tooltip',
                    ],
                    'constraints' => [
                        new Assert\NotBlank(
                            [
                                'message' => 'mautic.core.required',
                            ]
                        ),
                    ],
                ]
            );

            $builder->add(
                'username',
                NumberType::class,
                [
                    'label'      => 'mautic.random.smtp.column.username',
                    'label_attr' => ['class' => 'control-label'],
                    'attr'       => [
                        'class'  => 'form-control',
                        'tooltip' => 'mautic.random.smtp.column.tooltip',
                    ],
                    'required'=>false
                ]
            );

            $builder->add(
                'password',
                NumberType::class,
                [
                    'label'      => 'mautic.random.smtp.column.password',
                    'label_attr' => ['class' => 'control-label'],
                    'attr'       => [
                        'class'  => 'form-control',
                        'tooltip' => 'mautic.random.smtp.column.tooltip',
                    ],
                    'required'=>false
                ]
            );

            $builder->add(
                'port',
                NumberType::class,
                [
                    'label'      => 'mautic.random.smtp.column.port',
                    'label_attr' => ['class' => 'control-label'],
                    'attr'       => [
                        'class'  => 'form-control',
                        'tooltip' => 'mautic.random.smtp.column.port.tooltip',
                    ],
                    'required'=>false
                ]
            );

            $builder->add(
                'auth_mode',
                NumberType::class,
                [
                    'label'      => 'mautic.random.smtp.column.auth_mode',
                    'label_attr' => ['class' => 'control-label'],
                    'attr'       => [
                        'class'  => 'form-control',
                        'tooltip' => 'mautic.random.smtp.column.auth_mode.tooltip',
                    ],
                    'required'=>false
                ]
            );

            $builder->add(
                'encryption',
                NumberType::class,
                [
                    'label'      => 'mautic.random.smtp.column.encryption',
                    'label_attr' => ['class' => 'control-label'],
                    'attr'       => [
                        'class'  => 'form-control',
                        'tooltip' => 'mautic.random.smtp.column.encryption.tooltip',
                    ],
                    'required'=>false
                ]
            );
        }
    }
}
