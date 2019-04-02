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

    /**
     * @return string
     */
    private function generateCsvFromArray()
    {
        return 'host,username,password,port,auth_mode,encryption,fromEmail,fromName'."\r\n".'host2,username,password,port,auth_mode,encryption,fromEmail,fromName'."\r\n".'host3,username,password,port,auth_mode,encryption,fromEmail,fromName';
    }

    /**
     * @return array
     */
    private function getConfig()
    {
        return [
            'host' => 0,
            'username' => 1,
            'password' => 2,
            'port' => 3,
            'auth_mode' => 4,
            'encryption' => 5,
            'fromEmail' => 6,
            'fromName' => 7,
        ];
    }

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


    public function testhostColumnFind()
    {
        $integrationMock = $this->createMock(Integration::class);
        $integrationMock->method('getIsPublished')->willReturn(true);

        $smtpRandomizerIntegration = $this->createMock(RandomSmtpIntegration::class);
        $smtpRandomizerIntegration->method('getIntegrationSettings')->willReturn($integrationMock);


        $smtpRandomizerIntegration->method('mergeConfigToFeatureSettings')->willReturn(
            array_merge(['smtps'=> $this->generateCsvFromArray()], $this->getConfig())
        );

        $integrationHelperMock = $this->createMock(IntegrationHelper::class);
        $integrationHelperMock->method('getIntegrationObject')->willReturn($smtpRandomizerIntegration);
        $randomSmtpTransportMock = $this->createMock(RandomSmtpTransport::class);
        $randomSmtpTransportMock->method('setHost')->willReturnCallback(
            function ($host) {
                $this->assertTrue(isset($host));
            });

        (new SmtpRandomizer($integrationHelperMock))->randomize($randomSmtpTransportMock);

    }

    public function testhostColumnRandomGenerate()
    {
        $integrationMock = $this->createMock(Integration::class);
        $integrationMock->method('getIsPublished')->willReturn(true);

        $smtpRandomizerIntegration = $this->createMock(RandomSmtpIntegration::class);
        $smtpRandomizerIntegration->method('getIntegrationSettings')->willReturn($integrationMock);

        $smtpRandomizerIntegration->method('mergeConfigToFeatureSettings')->willReturn(
             array_merge(['smtps'=> $this->generateCsvFromArray()], $this->getConfig())
        );
        $integrationHelperMock = $this->createMock(IntegrationHelper::class);
        $integrationHelperMock->method('getIntegrationObject')->willReturn($smtpRandomizerIntegration);
        $randomSmtpTransportMock = $this->createMock(RandomSmtpTransport::class);
        $results = [];
        $randomSmtpTransportMock->method('setHost')->willReturnCallback(
            function ($host) use (&$results) {
                $results[] =  $host;
            });

        $messageMock = $this->createMock(\Swift_Mime_Message::class);
        $messageMock->expects($this->any())->method('setFrom')->willReturnCallback(
            function ($return) {
                $this->assertTrue(isset($return));
            });

        $randomizer = (new SmtpRandomizer($integrationHelperMock));
        for ($i = 0; $i < 100; $i++) {
             $randomizer->randomize($randomSmtpTransportMock, $messageMock);
            $uniqueResultsCount = count(array_unique($results));
            if ($uniqueResultsCount > 1) {
                break;
            }
        }
        $this->assertGreaterThan(1, $uniqueResultsCount);
    }


    public function testAllColumnsFind()
    {
        $integrationMock = $this->createMock(Integration::class);
        $integrationMock->method('getIsPublished')->willReturn(true);

        $smtpRandomizerIntegration = $this->createMock(RandomSmtpIntegration::class);
        $smtpRandomizerIntegration->method('getIntegrationSettings')->willReturn($integrationMock);


        $smtpRandomizerIntegration->method('mergeConfigToFeatureSettings')->willReturn(
            array_merge(['smtps'=> $this->generateCsvFromArray()], $this->getConfig())
        );

        $integrationHelperMock = $this->createMock(IntegrationHelper::class);
        $integrationHelperMock->method('getIntegrationObject')->willReturn($smtpRandomizerIntegration);
        $randomSmtpTransportMock = $this->createMock(RandomSmtpTransport::class);

        $randomSmtpTransportMock->expects($this->once())->method('setHost')->willReturnCallback(
        function ($host) {
            $this->assertTrue(isset($host));
        });

        $randomSmtpTransportMock->expects($this->once())->method('setPort')->willReturnCallback(
            function ($return) {
                $this->assertTrue(isset($return));
            });


        $randomSmtpTransportMock->expects($this->once())->method('setEncryption')->willReturnCallback(
            function ($return) {
                $this->assertTrue(isset($return));
            });

        $messageMock = $this->createMock(\Swift_Mime_Message::class);
        $messageMock->expects($this->once())->method('setFrom')->willReturnCallback(
            function ($return) {
                $this->assertTrue(isset($return));
            });

        (new SmtpRandomizer($integrationHelperMock))->randomize($randomSmtpTransportMock, $messageMock);

    }
}
