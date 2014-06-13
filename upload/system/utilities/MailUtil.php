<?php

/**
 * Provides helper methods for mail
 */
class MailUtil
{

    private static $instance;

    /**
     * Mail transport
     */
    private $transport;

    /**
     * Set config from admin 
     */ 
    private $config;
    
    /**
     * Returns instance
     */
    public static function getInstance($config)
    {
        if (is_null(self::$instance)) {
            self::$instance = new MailUtil($config);
        }

        return self::$instance;
    }

    /**
     * Instantiates this class
     */
    public function __construct($config)
    {
        $this->config = $config;
        $this->setMailTransport();
    }

    /**
     * Do email
     */
    public function sendEmail($mailData = array())
    {
        if (isset($mailData['email']) && isset($mailData['message'])) {
            // Create the message
            $message = Swift_Message::newInstance();
            $message->setTo($mailData['email']);
            $message->setBody(html_entity_decode($mailData['message'], ENT_QUOTES, 'UTF-8'), 'text/html');
            $message->setFrom($this->config->get('config_email'));
            $message->setSubject(html_entity_decode($mailData['subject'], ENT_QUOTES, 'UTF-8')); 

            if (isset($mailData['cc'])) {
                $message->setCc($mailData['cc']);
            }

            if (isset($mailData['bcc'])) {
                $message->setBcc($mailData['bcc']);
            }  

            $this->sendMessage($message);  
        }        
    }

     /**
     * Returns mail transport
     */
    private function setMailTransport()
    {
        // Create the SMTP configuration
        $this->transport = Swift_SmtpTransport::newInstance($this->config->get('config_smtp_host'), $this->config->get('config_smtp_port'), 'sslv3');
        $this->transport->setUsername($this->config->get('config_smtp_username'));
        $this->transport->setPassword($this->config->get('config_smtp_password'));   
    }

    /**
     * Send message
     */
    private function sendMessage(Swift_Message $message)
    {
        // Send the email
        $mailer = Swift_Mailer::newInstance($this->transport);
        $mailer->send($message);
    }
}