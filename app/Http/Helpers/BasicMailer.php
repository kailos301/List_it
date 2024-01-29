<?php

namespace App\Http\Helpers;

use App\Models\BasicSettings\Basic;
use Config;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class BasicMailer
{
  public static function sendMail($data)
  {
    // get the website title & mail's smtp information from db
    $info = Basic::select('website_title', 'smtp_status', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name')
      ->first();

    // if smtp status == 1, then set some value for PHPMailer
    if ($info->smtp_status == 1) {
      try {
        $smtp = [
          'transport' => 'smtp',
          'host' => $info->smtp_host,
          'port' => $info->smtp_port,
          'encryption' => $info->encryption,
          'username' => $info->smtp_username,
          'password' => $info->smtp_password,
          'timeout' => null,
          'auth_mode' => null,
        ];
        Config::set('mail.mailers.smtp', $smtp);
      } catch (\Exception $e) {
        Session::flash('error', $e->getMessage());
        return back();
      }
    }

    try {
      Mail::send([], [], function (Message $message) use ($data, $info) {
        $fromMail = $info->from_mail;
        $fromName = $info->from_name;
        $message->to($data['recipient'])
          ->subject($data['subject'])
          ->from($fromMail, $fromName)
          ->html($data['body'], 'text/html');

        if (array_key_exists('invoice', $data)) {
          $message->attach($data['invoice'], [
            'as' => 'Invoice',
            'mime' => 'application/pdf',
          ]);
        }
      });
      if (array_key_exists('sessionMessage', $data)) {
        Session::flash('success', $data['sessionMessage']);
      }
    } catch (\Exception $e) {
      Session::flash('warning', 'Mail could not be sent. Mailer Error: ' . $e);
    }
    return;
  }
}
