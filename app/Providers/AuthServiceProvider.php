<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class AuthServiceProvider extends ServiceProvider
{
	/**
	 * The model to policy mappings for the application.
	 *
	 * @var array<class-string, class-string>
	 */
	protected $policies = [
		// 'App\Models\Model' => 'App\Policies\ModelPolicy',
	];

	/**
	 * Register any authentication / authorization services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->registerPolicies();

		VerifyEmail::toMailUsing(function ($notifiable, $url) {
			return (new MailMessage())
				->subject('Please verify your Email Address')
				->action('Verify Email Address', $url)
				->view('email.verify', compact('url'));
		});

		VerifyEmail::createUrlUsing(function ($notifiable) {
			$params = [
				'expires' => Carbon::now()
					->addMinutes(60)
					->getTimestamp(),
				'id'   => $notifiable->getKey(),
				'hash' => sha1($notifiable->getEmailForVerification()),
			];

			ksort($params);

			// then create API url for verification. my API have `/api` prefix,
			// so I don't want to show that url to users
			$url = URL::route('verification.verify', $params);

			// get APP_KEY from config and create signature
			$key = config('app.key');
			$signature = hash_hmac('sha256', $url, $key);

			//generate url for yous SPA page to send it to user
			return 'http://localhost:5173' .
				'/email-verify/' .
				$params['id'] .
				'/' .
				$params['hash'] .
				'?expires=' .
				$params['expires'] .
				'&signature=' .
				$signature;
		});
	}
}
