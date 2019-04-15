<?php

namespace App\Http\Controllers;

use App\Mail\Newsletter;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function store($data)
    {
        $userModel = new User;
        $user      = $userModel->create($data);
    }

    public function sendMail(Request $request)
    {
        $subject = 'reservation';
        $message = 'hello';

        $this->store($request->all());

        $mail = new Newsletter($subject, $message);
        Mail::to('ebrahimes@gmail.com')->send($mail);
    }
}
