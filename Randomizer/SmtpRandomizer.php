<?php

/*
 * @copyright   2019 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\MauticRandomSmtpBundle\Randomizer;

use Mautic\CoreBundle\Helper\ArrayHelper;
use Mautic\PluginBundle\Helper\IntegrationHelper;
use MauticPlugin\MauticRandomSmtpBundle\Exception\HostNotExistinCsvRowExpection;
use MauticPlugin\MauticRandomSmtpBundle\Exception\IntegrationDisableException;
use MauticPlugin\MauticRandomSmtpBundle\Exception\SmtpCsvListNotExistException;
use MauticPlugin\MauticRandomSmtpBundle\Swiftmailer\Transport\RandomSmtpTransport;

class SmtpRandomizer
{
    /** @var  array */
    private $config;

    /** @var  array */
    private $smtps;

    /**
     * {@inheritdoc}
     */
    public function __construct(IntegrationHelper $integrationHelper)
    {
        $integration = $integrationHelper->getIntegrationObject('RandomSmtp');

        if (!$integration || $integration->getIntegrationSettings()->getIsPublished() !== true) {
            throw new IntegrationDisableException('Integration RandomSmtp doesn\'t exist or is unpublished');
        }

        $config = $this->config = $integration->mergeConfigToFeatureSettings();
        $smtps = explode("\n", $config['smtps']);
        $smtp = end($smtps);
        if (empty($smtp)) {
            throw new SmtpCsvListNotExistException('Smtp CSV list not exist. Please setup it in plugin setting.');
        }

        $this->smtps = array_map('str_getcsv', $smtps);

    }

    /**
     * @throws \Exception
     */
    public function randomize(RandomSmtpTransport $randomSmtpTransport)
    {
        $smtps = $this->smtps;
        shuffle($smtps);
        $smtp = end($smtps);
        if (!$host = ArrayHelper::getValue($this->getConfigParamter('host'), $smtp)) {
            throw new HostNotExistinCsvRowExpection('Can\'t find host on column possition '.sprintf('"%s"', $this->getConfigParamter('host')));
        }

        $randomSmtpTransport->setHost($host);
        $randomSmtpTransport->setPort(ArrayHelper::getValue('post', $smtp, 25));
        $randomSmtpTransport->setEncryption(ArrayHelper::getValue($this->getConfigParamter('encryption'), $smtp, ''));
        $randomSmtpTransport->setAuthMode(ArrayHelper::getValue($this->getConfigParamter('auth_mode'), $smtp, ''));
        $randomSmtpTransport->setUsername(ArrayHelper::getValue($this->getConfigParamter('username'), $smtp, ''));
        $randomSmtpTransport->setPassword(ArrayHelper::getValue($this->getConfigParamter('password'), $smtp, ''));
    }

    /**
     * @param $key
     *
     * @return int
     */
    private function getConfigParamter($key)
    {
        if (isset($this->config[$key]) && $this->config[$key] !== '') {
            return (int) $this->config[$key];
        }
    }

}
