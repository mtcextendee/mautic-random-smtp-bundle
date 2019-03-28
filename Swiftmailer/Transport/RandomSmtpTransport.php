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

class RandomSmtpTransport extends \Swift_SmtpTransport
{
    /**
     * @var SmtpRandomizer
     */
    private $smtpRandomizer;


    /**
     * RandomSmtpTransport constructor.
     *
     * @param SmtpRandomizer $smtpRandomizer
     * @param int            $port
     * @param null           $security
     */
    public function __construct(SmtpRandomizer $smtpRandomizer, $port = 25, $security = null)
    {
        $this->smtpRandomizer = $smtpRandomizer;
        parent::__construct('localhost');

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
        $this->smtpRandomizer->randomize();
        parent::send($message, $failedRecipients);
    }
}
