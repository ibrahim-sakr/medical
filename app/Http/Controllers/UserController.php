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
        $message ='hello' ;

        $mail = new Newsletter($subject, $message);
        Mail::to('eng.hader2012@gmail.com')->send($mail);

        $user = $this->store($request->all());
    }
}
