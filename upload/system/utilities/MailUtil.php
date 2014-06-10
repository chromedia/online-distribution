<?php

/**
 * Provides helper methods for mail
 */
class MailUtil
{
    /**
     * Do email
     */
    public function sendEmail($mailData = array())
    {
        if (isset($mailData['email']) && isset($mailData['message'])) {
            // Send Email to Account User
            $mail = new Mail();
            $mail->protocol = $this->config->get('config_mail_protocol');
            $mail->parameter = $this->config->get('config_mail_parameter'); //'-f' . $this->config->get('config_email');
            $mail->hostname = $this->config->get('config_smtp_host');
            $mail->username = $this->config->get('config_smtp_username');
            $mail->password = $this->config->get('config_smtp_password');
            $mail->port = $this->config->get('config_smtp_port');
            $mail->timeout = $this->config->get('config_smtp_timeout');

            $mail->setTo($mailData['email']);
            $mail->setFrom($this->config->get('config_email'));
            $mail->setSender($this->config->get('config_name'));
            $mail->setText(html_entity_decode($mailData['message'], ENT_QUOTES, 'UTF-8'));
        
            if (isset($mailData['subject'])) {
                $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
            }

            $mail->send();
        }        
    }
}