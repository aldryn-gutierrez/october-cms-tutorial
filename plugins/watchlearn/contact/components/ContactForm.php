<?php

namespace Watchlearn\Contact\Components;

use Cms\Classes\ComponentBase;
use Input;
use Mail;
use Validator;
use Redirect;


class ContactForm extends ComponentBase 
{
	public function componentDetails()
	{
		return [
			'name' => 'Contact Form',
			'description' => 'Simple contact form',
		];
	}

	public function onSend()
	{
		$data = post();
		$rules = [
			'name' => 'required',
			'email' => 'required|email|unique:users',
		];

		$validator = Validator::make($data, $rules);

		if ($validator->fails()) {
			// return [
			// 	'#result' => $this->renderPartial(
			// 		'contactform::messages',
			// 		['fieldMsgs' => $validator->messages()]
			// 	)
			// ];

			throw new \ValidationException($validator);
		}

		$vars = [
			'name' => Input::get('name'), 
			'email' => Input::get('email'),
			'contact' => Input::get('contact'),
		];

		Mail::send('watchlearn.contact::mail.message', $vars, function($message){
			$message->to('aldrynshopping@gmail.com', 'Admin Person');
			$message->subject('New message from contact form');
		});
	}
}