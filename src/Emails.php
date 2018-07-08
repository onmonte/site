<?php

namespace Monte;

use Monte\Resources\Api;

class Emails extends Api
{

    public static function send($subject, $html, $email, $name)
    {
        return self::request('/emails/send', [], [
            'subject' => $subject,
            'html' => $html,
            'email' => $email,
            'name' => $name,
        ], false, false);
    }

}