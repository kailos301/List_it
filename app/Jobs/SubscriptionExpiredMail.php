<?php

namespace App\Jobs;

use App\Http\Helpers\MegaMailer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SubscriptionExpiredMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $vendor;
    public $bs;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($vendor, $bs)
    {
        $this->vendor = $vendor;
        $this->bs = $bs;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $mailer = new MegaMailer();

        $data = [
            'toMail' => $this->vendor->email,
            'toName' => $this->vendor->fname,
            'username' => $this->vendor->username,
            'website_title' => $this->bs->website_title,
            'templateType' => 'membership_expired',
            'login_link' => '<a href="' . route('vendor.login') . '">Login</a>'
        ];
        $mailer->mailFromAdmin($data);
    }
}
