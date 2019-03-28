<?php

/*
 * @copyright   2019 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\MauticRandomSmtpBundle\Swiftmailer\Transport;

use MauticPlugin\MauticRandomSmtpBundle\Randomizer\SmtpRandomizer;
use Monolog\Logger;

class RandomSmtpTransport extends \Swift_SmtpTransport
{
    /**
     * @var SmtpRandomizer
     */
    private $smtpRandomizer;

    /**
     * @var Logger
     */
    private $logger;


    /**
     * RandomSmtpTransport constructor.
     *
     * @param SmtpRandomizer $smtpRandomizer
     * @param Logger         $logger
     * @param null           $security
     *
     */
    public function __construct(SmtpRandomizer $smtpRandomizer, Logger $logger, $security = null)
    {
        $this->smtpRandomizer = $smtpRandomizer;
        $this->logger = $logger;
        parent::__construct('localhost');
        define('MAUTIC_SMTP_RANDOM_FROM_CONSTRUCTOR', 1);
        $this->setRandomSmtpServer();

    }



    /**
     * @param \Swift_Mime_Message $message
     * @param null                $failedRecipients
     *
     * @return int|void
     *
     * @throws \Exception
     */
    public function send(\Swift_Mime_Message $message, &$failedRecipients = null)
    {
        if (!defined('MAUTIC_SMTP_RANDOM_FROM_CONSTRUCTOR')) {
            $this->setRandomSmtpServer();
        }
        parent::send($message, $failedRecipients);
    }

    /**
     * Set random SMTP server
     */
    private function setRandomSmtpServer()
    {
        try {
            $this->smtpRandomizer->randomize($this);
            $this->logger->debug(sprintf('Send by random SMTP server: %s with username %s', $this->getHost(), $this->getUsername()));
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
        }
    }
}
