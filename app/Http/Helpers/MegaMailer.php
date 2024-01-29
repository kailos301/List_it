<?php

namespace App\Http\Helpers;

use App\Models\Language;
use App\Models\BasicExtended;
use App\Models\BasicSettings\Basic;
use App\Models\BasicSettings\MailTemplate;
use App\Models\EmailTemplate;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use App\Models\User\UserEmailTemplate;
use Config;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class MegaMailer
{

  public function mailFromAdmin($data)
  {
    $temp = MailTemplate::where('mail_type', '=', $data['templateType'])->first();

    $body = $temp->mail_body;
    if (array_key_exists('username', $data)) {
      $body = preg_replace("/{username}/", $data['username'], $body);
    }
    if (array_key_exists('replaced_package', $data)) {
      $body = preg_replace("/{replaced_package}/", $data['replaced_package'], $body);
    }
    if (array_key_exists('removed_package_title', $data)) {
      $body = preg_replace("/{removed_package_title}/", $data['removed_package_title'], $body);
    }
    if (array_key_exists('package_title', $data)) {
      $body = preg_replace("/{package_title}/", $data['package_title'], $body);
    }
    if (array_key_exists('package_price', $data)) {
      $body = preg_replace("/{package_price}/", $data['package_price'], $body);
    }
    if (array_key_exists('discount', $data)) {
      $body = preg_replace("/{discount}/", $data['discount'], $body);
    }
    if (array_key_exists('total', $data)) {
      $body = preg_replace("/{total}/", $data['total'], $body);
    }
    if (array_key_exists('activation_date', $data)) {
      $body = preg_replace("/{activation_date}/", $data['activation_date'], $body);
    }
    if (array_key_exists('expire_date', $data)) {
      $body = preg_replace("/{expire_date}/", $data['expire_date'], $body);
    }
    if (array_key_exists('last_day_of_membership', $data)) {
      $body = preg_replace("/{last_day_of_membership}/", $data['last_day_of_membership'], $body);
    }
    if (array_key_exists('login_link', $data)) {
      $body = preg_replace("/{login_link}/", $data['login_link'], $body);
    }
    if (array_key_exists('customer_name', $data)) {
      $body = preg_replace("/{customer_name}/", $data['customer_name'], $body);
    }
    if (array_key_exists('verification_link', $data)) {
      $body = preg_replace("/{verification_link}/", $data['verification_link'], $body);
    }
    if (array_key_exists('website_title', $data)) {
      $body = preg_replace("/{website_title}/", $data['website_title'], $body);
    }

    if (session()->has('lang')) {
      $currentLang = Language::where('code', session()->get('lang'))->first();
    } else {
      $currentLang = Language::where('is_default', 1)->first();
    }
    $be = Basic::first();

    //laravel facade mailer 
    if ($be->smtp_status == 1) {
      try {
        $smtp = [
          'transport' => 'smtp',
          'host' => $be->smtp_host,
          'port' => $be->smtp_port,
          'encryption' => $be->encryption,
          'username' => $be->smtp_username,
          'password' => $be->smtp_password,
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
      if (array_key_exists('mail_subject', $data)) {
        $mail_subject = $data['mail_subject'];
      } else {
        $mail_subject = $temp->mail_subject;
      }
      Mail::send([], [], function (Message $message) use ($data, $be, $body, $mail_subject) {
        $fromMail = $be->from_mail;
        $fromName = $be->from_name;

        $message->to($data['toMail'])
          ->subject($mail_subject)
          ->from($fromMail, $fromName)
          ->html($body, 'text/html');

        if (array_key_exists('membership_invoice', $data)) {
          $message->attach(public_path('assets/front/invoices/' . $data['membership_invoice']), [
            'as' => 'Invoice',
            'mime' => 'application/pdf',
          ]);
        }
      });
    } catch (\Exception $e) {
      Session::flash('error', $e->getMessage());
      return back();
    }
    //laravel facade mailer end
  }


  public function mailToAdmin($data)
  {
    $be = Basic::first();
    if ($be->smtp_status == 1) {
      try {
        $smtp = [
          'transport' => 'smtp',
          'host' => $be->smtp_host,
          'port' => $be->smtp_port,
          'encryption' => $be->encryption,
          'username' => $be->smtp_username,
          'password' => $be->smtp_password,
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
      Mail::send([], [], function (Message $message) use ($data, $be) {
        $fromMail = $be->from_mail;
        $fromName = $be->from_name;
        $message->to($be->to_mail)
          ->subject($data['subject'])
          ->from($fromMail, $fromName)
          ->html($data['body'], 'text/html');
      });
    } catch (\Exception $e) {
      Session::flash('error', $e->getMessage());
      return back();
    }
  }
}
