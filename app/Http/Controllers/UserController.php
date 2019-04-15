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

    public function mail(Request $request)
    {
        $subject = 'reservation';
        $message = 'hello';

        $this->store($request->all());

        $mail = new Newsletter($subject, $message);
        Mail::to('ebrahimes@gmail.com')->send($mail);
    }

    public function notification(Request $request)
    {
        $payload = [
            'to'              => '/topics/topic',
            'priority'        => 'high',
            "mutable_content" => TRUE,
            "notification"    => [
                "title" => 'My Noti',
                "body"  => 'My Noti Body',
            ],
            'data'            => [
                'hema' => 'sakr'
            ],
        ];

        $headers = [
            'Authorization:key=' . env('FCM_SERVER_KEY'),
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, env('FCM_BASE_URL'));
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        $result = curl_exec($ch);
        curl_close($ch);
        var_dump($result);
    }
}
