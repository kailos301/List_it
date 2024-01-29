<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\MiscellaneousController;
use App\Http\Requests\MailFromUserRequest;
use App\Models\BasicSettings\Basic;
use Config;
use DB;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class ContactController extends Controller
{
	public function contact()
	{
		$misc = new MiscellaneousController();

		$language = $misc->getLanguage();

		$queryResult['seoInfo'] = $language->seoInfo()->select('meta_keyword_contact', 'meta_description_contact')->first();

		$queryResult['pageHeading'] = $misc->getPageHeading($language);

		$queryResult['bgImg'] = $misc->getBreadcrumb();

		$queryResult['info'] = Basic::select('email_address', 'contact_number', 'address', 'google_recaptcha_status', 'latitude', 'longitude')->firstOrFail();

		return view('frontend.contact', $queryResult);
	}

	public function sendMail(MailFromUserRequest $request)
	{
		$info = DB::table('basic_settings')
			->select('website_title', 'smtp_status', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name', 'to_mail')
			->first();
		$name = $request->name;
		$to = $info->to_mail;
		$subject = $request->subject;

		$message = '<p>A new quote request has been sent.<br/><strong>Client Name: </strong>' . $name . '<br/><strong>Client Mail: </strong>' . $request->email . '</p><p>Message : ' . $request->message . '</p>';

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
		$data = [
			'to' => $to,
			'subject' => $subject,
			'message' => $message,
		];
		try {
			Mail::send([], [], function (Message $message) use ($data, $info) {
				$fromMail = $info->from_mail;
				$fromName = $info->from_name;
				$message->to($data['to'])
					->subject($data['subject'])
					->from($fromMail, $fromName)
					->html($data['message'], 'text/html');
			});
			Session::flash('success', 'A contact request was sent successfully');
		} catch (\Exception $e) {
			Session::flash('error', 'Something went wrong.');
			return back();
		}

		return redirect()->back();
	}
}
