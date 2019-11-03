<?php
defined('_EXEC') or die;

require PATH_COMPONENTS . 'com_phpmailer/PHPMailerAutoload.php';

function send_email(
    $address = FALSE,
    $replyTo = FALSE,
    $cc = FALSE,
    $bcc = FALSE,
    $attachment = FALSE,
    $subject = FALSE,
    $body = FALSE,
    $altBody = FALSE
)
{
    $mail = new PHPMailer( true );
    try
    {
        $mail->SMTPAuth     = Configuration::$smtp_auth;
        $mail->Host         = Configuration::$smtp_host;
        $mail->Port         = Configuration::$smtp_port;
        $mail->Username     = Configuration::$smtp_user;
        $mail->Password     = Configuration::$smtp_pass;
        $mail->SMTPSecure   = Configuration::$smtp_secure;
        $mail->SMTPAutoTLS  = false;

        $mail->isSMTP();
        $mail->SetLanguage( $_COOKIE['lang'], dirname(__FILE__) . '/language/' );
        $mail->SMTPDebug = 0;
        $mail->Debugoutput = 'html';
        $mail->isHTML( true );

        $mail->setFrom( Configuration::$smtp_user, Configuration::$webPage );

        if(!isset($_SERVER['HTTPS']))
        {
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer'       => false,
                    'verify_peer_name'  => false,
                    'allow_self_signed' => true
                )
            );
        }

        if($address != FALSE)
        {
            if(is_array($address))
            {
                foreach($address as $key => $value)
                    $mail->addAddress($key, $value);
            }
            else
                $mail->addAddress($address);
        }

        if ($replyTo != FALSE)
        {
            foreach ($replyTo as $key => $value)
                $mail->addReplyTo($key, $value);
        }

        if ($cc != false)
        {
            foreach ($cc as $value)
                $mail->addCC($value);
        }

        if ($bcc != false)
        {
            foreach ($bcc as $value)
                $mail->addBCC($value);
        }

        if ($attachment != false)
        {
            foreach ($attachment as $key => $value)
                $mail->addAttachment($key, $value);
        }

        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->AltBody = $altBody;
        $mail->CharSet = 'UTF-8';

        $mail->send();

        return 'success';
    }
    catch ( phpmailerException $e )
    {
        echo $e->errorMessage();
    }
    catch ( Exception $e )
    {
        echo $e->getMessage();
    }
}
