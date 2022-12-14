<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Guards\JwtGuard;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
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

         // Custom guard for private channels
		Auth::extend('jwt', function ($app, $name, array $config) {
			return new JwtGuard(Auth::createUserProvider($config['provider']));
		});

		// Create custom blade file for user verification
		VerifyEmail::toMailUsing(function ($notifiable, $url) {
			return (new MailMessage())
				->subject('Please verify your Email Address')
				->action('Verify Email Address', $url)
				->view('email.verify', compact('url'));
		});

		// Create custom url for user verification
		VerifyEmail::createUrlUsing(function ($notifiable) {
			$params = [
				'expires' => Carbon::now()
					->addMinutes(60)
					->getTimestamp(),
				'id'   => $notifiable->getKey(),
				'hash' => sha1($notifiable->getEmailForVerification()),
			];

			ksort($params);

			// then create API url for verification
			$url = URL::route('verification.verify', $params);

			// get APP_KEY from config and create signature
			$key = config('app.key');
			$signature = hash_hmac('sha256', $url, $key);

			//generate url for Vue SPA page to send it to user
			return 'http://127.0.0.1:5173' .
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
