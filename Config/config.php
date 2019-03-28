<?php

/*
 * @copyright   2019 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

return [
    'name'        => 'Mautic Random Smtp Bundle',
    'description' => 'Random Smtp integration for Mautic',
    'author'      => 'mtcextendee.com',
    'version'     => '1.0.0',
    'services' => [
        'forms'   => [
        ],
        'helpers' => [],
        'other'   => [
            'mautic.transport.random' => [
                'class'        => \MauticPlugin\MauticRandomSmtpBundle\Swiftmailer\Transport\RandomSmtpTransport::class,
                'arguments' => [
                    'mautic.random.smtp.randomizer',
                ],
                'tag'          => 'mautic.email_transport',
                'tagArguments' => [
                    \Mautic\EmailBundle\Model\TransportType::TRANSPORT_ALIAS => 'mautic.email.config.mailer_transport.random_smtp',
                ],
            ],
            'mautic.random.smtp.randomizer' => [
                'class'        => MauticPlugin\MauticRandomSmtpBundle\Randomizer\SmtpRandomizer::class,
                'arguments' => [
                    'mautic.helper.integration',
                ],
            ],
        ],
        'models'       => [],
        'integrations' => [
            'mautic.integration.random_smtp' => [
                'class' => \MauticPlugin\MauticRandomSmtpBundle\Integration\RandomSmtpIntegration::class,
            ],
        ],
    ],
    'routes'     => [],
    'menu'       => [],
    'parameters' => [
    ],
];
