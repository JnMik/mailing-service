<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Jm
 * Date: 15-10-06
 * Time: 21:35
 * To change this template use File | Settings | File Templates.
 */

namespace Support3w\MailingService;

use PHPMailer;
use phpmailerException;

class MailingService
{

    /**
     * @var \PHPMailer
     */
    private $mailer;

    /**
     * @var String
     */
    private $username;

    /**
     * @var String
     */
    private $password;

    /**
     * @param string $host
     * @param int $port
     * @param int $wordWrap
     */
    public function __construct($host = 'relais.videotron.ca', $port = 25, $wordWrap = 50)
    {
        $this->mailer = new PHPMailer(true);
        $this->mailer->IsSMTP(); // telling the class to use SMTP
        $this->mailer->WordWrap = $wordWrap;
        $this->mailer->Host = $host;
        $this->mailer->Port = ($port) ? $port : 25;
    }

    /**
     * @param $username
     * @param $password
     * @param $ssl
     */
    public function auth($username, $password, $ssl)
    {
        $this->username = $username;
        $this->password = $password;
        if ($ssl) {
            $this->mailer->SMTPAuth = true;
            $this->mailer->SMTPSecure = "ssl";
        }
    }

    /**
     * @param array $receiver
     * @param $from
     * @param $fromName
     * @param $subject
     * @param $description
     * @return bool
     * @throws \Exception
     * @throws \phpmailerException
     * @throws \Exception
     */
    public function sendEmail(array $receiver, $from, $fromName, $subject, $description)
    {

        try {

            $this->mailer->From = $from;
            $this->mailer->FromName = $fromName;
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $description;

            if ($this->username) {
                $this->mailer->Username = $this->username;
            }

            if ($this->password) {
                $this->mailer->Password = $this->password;
            }

            foreach ($receiver as $email) {
                $this->mailer->AddAddress($email);
            }

            if (!$this->mailer->Send()) {
                throw new \Exception('Message could not be sent');
            }

            return true;

        } catch (phpmailerException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw $e;
        }

    }

}