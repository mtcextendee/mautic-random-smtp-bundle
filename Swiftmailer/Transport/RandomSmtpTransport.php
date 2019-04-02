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
        $this->setRandomSmtpServer($message);
        parent::send($message, $failedRecipients);
    }

    /**
     * Set random SMTP server
     *
     * @param Swift_Mime_Message $message
     */
    private function setRandomSmtpServer(\Swift_Mime_Message $message = null)
    {
        try {
            $this->logger->info(sprintf('Send by random SMTP server: %s with username %s and sender email %s', $this->getHost(), $this->getUsername(), implode(',', $message ? array_keys($message->getFrom()) : [])));
            $this->smtpRandomizer->randomize($this, $message);
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
        }
    }
}
