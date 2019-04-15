<?php
/**
 * Created by PhpStorm.
 * User: hadeer
 * Date: 4/14/19
 * Time: 1:07 AM
 */

namespace App\Http\Controllers;

use App\Mail\Newsletter;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Messaging\CloudMessage;

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
        $user = $userModel->create($data);
    }

    public function sendMail(Request $request)
    {
        $subject = 'reservation';
        $message = 'hello';

        $mail = new Newsletter($subject, $message);
        Mail::to('eng.hader2012@gmail.com')->send($mail);

        $user = $this->store($request->all());
    }

    public function sendNotification(Request $request)
    {
        $serviceAccount = ServiceAccount::fromJsonFile(base_path() . '/medical-system-firebase.json');
        $firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->create();

        $topic = 'a-topic';
        $notification = $request->notification;
        $data =$request->data;
        $message = CloudMessage::fromArray([
            'topic' => $topic,
//            'notification' => [$notification], // optional
//            'data' => [$data], // optional
        ]);
        $messaging = $firebase->getMessaging();
        $messaging->send($message);
    }
}
