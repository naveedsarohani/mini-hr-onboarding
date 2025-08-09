<?php

namespace Core;

use SendGrid;
use SendGrid\Mail\Mail as SendGridMail;

class Mail
{
    protected static ?SendGrid $sendGrid = null;

    protected static function init(): void
    {
        if (!self::$sendGrid) self::$sendGrid = new SendGrid(config('services.sendgrid.key'));
    }

    public static function send($to, $name, $subject, $template, $data = [])
    {
        self::init();

        $email = new SendGridMail();
        $email->setFrom(config('services.sendgrid.email'), config('app.name'));
        $email->setSubject($subject);
        $email->addTo($to, $name);

        $body = self::template($template, $data);
        $email->addContent("text/html", $body);

        $response = self::$sendGrid->send($email);
        return $response->statusCode() >= 200 && $response->statusCode() < 300;
    }

    protected static function template($template, $data)
    {
        extract($data);
        ob_start();
        require_once base_path("/resources/email_templates/{$template}.php");
        return ob_get_clean();
    }
}
