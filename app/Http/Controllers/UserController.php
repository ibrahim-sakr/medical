<?php

namespace App\Http\Controllers;

use App\Mail\Newsletter;
use App\User;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * @param array $data
     * @return string
     * @throws Exception
     * @author Ibrahim Sakr <ibrahim.sakr@tajawal.com>
     */
    private function store(array $data): string
    {
        $validator = Validator::make($data, [
            'name'        => ['required', 'min:3'],
            'phone'       => ['required', 'numeric'],
            'date'        => ['required', 'date'],
            'doctor_name' => ['required', 'min:3'],
        ]);

        if ($validator->fails()) {
            throw new Exception(json_encode($validator->errors()), 400);
        }

        // check if the user exists before
        $title = 'Registered User';
        $user  = User::where(([
            ['phone', $data['phone']],
            ['date', $data['date']],
            ['doctor_name', $data['doctor_name']],
        ]))->first();

        if ($user === NULL) {
            // create it
            User::create($data);
        } else {
            $title = 'Updated User';

            // update user
            $user->update($data);
        }

        return $title;
    }

    /**
     * @param Request $request
     * @throws Exception
     * @author Ibrahim Sakr <ibrahim.sakr@tajawal.com>
     */
    public function mail(Request $request)
    {
        $body          = $request->all();
        $body['title'] = $this->store($body);

        $mail = new Newsletter($body);
        Mail::to('ask.habdallah@gmail.com')->send($mail);
    }

    /**
     * @param Request $request
     * @author Ibrahim Sakr <ibrahim.sakr@tajawal.com>
     */
    public function notification(Request $request)
    {
        Log::info(json_encode($request->all()));

//        $xml = file_get_contents(base_path() . '/dummy.xml');
//        $xml = simplexml_load_string($xml);
//
//        $channelName = (string)$xml->entry->author->name;
//        $videoUrl    = (string)$xml->entry->link->attributes()['href'];
//        $videoTitle  = (string)$xml->entry->title;
//
//        $payload = [
//            'to'              => '/topics/' . env('FCM_TOPIC'),
//            'priority'        => 'high',
//            "mutable_content" => TRUE,
//            "notification"    => [
//                "title" => $channelName . ' :: New Video',
//                "body"  => 'a new video published on ' . $channelName . ' with title ' . $videoTitle,
//            ],
//            'data'            => [
//                'video_url' => $videoUrl,
//            ],
//        ];
//
//        $headers = [
//            'Authorization' => 'key=' . env('FCM_SERVER_KEY'),
//            'Content-Type'  => 'application/json',
//        ];
//
//        $this->sendHttp(env('FCM_BASE_URL'), $payload, $headers);
    }

    /**
     * @param string $url
     * @param array $body
     * @param array $headers
     * @author Ibrahim Sakr <ibrahim.sakr@tajawal.com>
     */
    private function sendHttp(string $url, array $body, array $headers = [])
    {
        (new Client())->post(
            $url,
            [
                'headers' => $headers,
                'json'    => $body,
            ]
        );
    }
}
