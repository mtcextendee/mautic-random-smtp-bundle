<?php

/*
 * @copyright   2018 Mautic Contributors. All rights reserved
 * @author      Mautic, Inc.
 *
 * @link        https://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\MauticRandomSmtpBundle\Tests\Randomizer;


use Mautic\PluginBundle\Entity\Integration;
use Mautic\PluginBundle\Helper\IntegrationHelper;
use MauticPlugin\MauticRandomSmtpBundle\Exception\HostNotExistinCsvRowExpection;
use MauticPlugin\MauticRandomSmtpBundle\Exception\IntegrationDisableException;
use MauticPlugin\MauticRandomSmtpBundle\Exception\SmtpCsvListNotExistException;
use MauticPlugin\MauticRandomSmtpBundle\Integration\RandomSmtpIntegration;
use MauticPlugin\MauticRandomSmtpBundle\Randomizer\SmtpRandomizer;
use MauticPlugin\MauticRandomSmtpBundle\Swiftmailer\Transport\RandomSmtpTransport;

class SmtpRandomizerTest extends \PHPUnit_Framework_TestCase
{

    public function testIntegrationNotExist()
    {
        $this->expectException(IntegrationDisableException::class);
        $integrationHelperMock = $this->createMock(IntegrationHelper::class);
        $integrationHelperMock->method('getIntegrationObject')->willReturn('');
        new SmtpRandomizer($integrationHelperMock);
    }

    public function testIntegrationDisabled()
    {
        $this->expectException(IntegrationDisableException::class);

        $integrationMock = $this->createMock(Integration::class);
        $integrationMock->method('getIsPublished')->willReturn(false);

        $smtpRandomizerIntegration = $this->createMock(RandomSmtpIntegration::class);
        $smtpRandomizerIntegration->method('getIntegrationSettings')->willReturn($integrationMock);

        $integrationHelperMock = $this->createMock(IntegrationHelper::class);
        $integrationHelperMock->method('getIntegrationObject')->willReturn($smtpRandomizerIntegration);

        new SmtpRandomizer($integrationHelperMock);
    }

    public function testSmtpCsvListNotExistException()
    {
        $this->expectException(SmtpCsvListNotExistException::class);

        $integrationMock = $this->createMock(Integration::class);
        $integrationMock->method('getIsPublished')->willReturn(true);

        $smtpRandomizerIntegration = $this->createMock(RandomSmtpIntegration::class);
        $smtpRandomizerIntegration->method('getIntegrationSettings')->willReturn($integrationMock);

        $csvData = '';
        $smtpRandomizerIntegration->method('mergeConfigToFeatureSettings')->willReturn(['smtps'=>$csvData]);

        $integrationHelperMock = $this->createMock(IntegrationHelper::class);
        $integrationHelperMock->method('getIntegrationObject')->willReturn($smtpRandomizerIntegration);

        new SmtpRandomizer($integrationHelperMock);
    }

    public function testhostColumnNotFindException()
    {
        $this->expectException(HostNotExistinCsvRowExpection::class);

        $integrationMock = $this->createMock(Integration::class);
        $integrationMock->method('getIsPublished')->willReturn(true);

        $smtpRandomizerIntegration = $this->createMock(RandomSmtpIntegration::class);
        $smtpRandomizerIntegration->method('getIntegrationSettings')->willReturn($integrationMock);


            //host2;,username2,password2,port2";
        $smtpRandomizerIntegration->method('mergeConfigToFeatureSettings')->willReturn(
            [
                'smtps'=> $this->generateCsvFromArray(),
                'host' => 9
            ]);

        $integrationHelperMock = $this->createMock(IntegrationHelper::class);
        $integrationHelperMock->method('getIntegrationObject')->willReturn($smtpRandomizerIntegration);
        $randomSmtpTransportMock = $this->createMock(RandomSmtpTransport::class);

        (new SmtpRandomizer($integrationHelperMock))->randomize($randomSmtpTransportMock);
    }

    /**
     * @return string
     */
    private function generateCsvFromArray()
    {
        return 'host,username,password,port'."\r\n".'host2,username2,password2,port2'."\r\n".'host3,username3,password3,port3';
    }
}
