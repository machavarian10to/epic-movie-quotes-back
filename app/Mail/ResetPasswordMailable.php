<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMailable extends Mailable
{
	use Queueable, SerializesModels;

	public $token;

	public $email;

	public function __construct($token, $email)
	{
		$this->token = $token;
		$this->email = $email;
	}

	public function build(): ResetPasswordMailable
	{
		return $this->view('email.reset')->with([
			'url'   => 'http://localhost:5173/reset-password?token=' . $this->token,
		]);
	}
}
