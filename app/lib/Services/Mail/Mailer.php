<?php namespace Lib\Services\Mail;

use Mail, App;

class Mailer
{
	/**
	 * Composes and sends email with provided data.
	 * 
	 * @param  string $view
	 * @param  array  $data 
	 * @param  string $email
	 * @param  string $subject
	 * @return void
	 */
	public function send($view, $data, $email = null, $subject = null)
	{
		if ( ! $email) $email = $data['email'];
		if ( ! $subject) $subject = $data['subject'];

		try
		{
			Mail::send($view, $data, function($message) use($data, $email, $subject)
			{
    			$message->to($email)->subject($subject);
			});
		}
		//send out activation code, then rethrow the exception.
		catch(\Swift_TransportException $e)
		{
			throw new \Swift_TransportException($e->getMessage());
		}
	}

	/**
	 * Sends mail from contact us form.
	 * 
	 * @param  array  $input
	 * @return void
	 */
	public function sendContactUs(array $input)
	{
		//get contact us email for db
		$options = App::make('Options');
		$email = $options->getContactEmail();

		if ($email)
		{
			Mail::send('Emails.Contact', $input, function($message) use($email)
			{
    			$message->to($email)->subject( trans('main.contact email subject') );
			});
		}
	}
}