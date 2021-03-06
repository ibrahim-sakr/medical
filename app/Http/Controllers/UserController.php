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

/**
 * Class UserController
 * @package App\Http\Controllers
 * @author Ibrahim Sakr <ibrahim.sakr@tajawal.com>
 */
class UserController extends Controller
{
    /**
     * @param array $data
     * @return array
     * @author Ibrahim Sakr <ibrahim.sakr@tajawal.com>
     */
    private function store(array $data): array
    {
        $validator = Validator::make($data, [
            'name'                => ['required', 'min:3'],
            'phone'               => ['required', 'numeric'],
            'datOfSurgery'        => ['required', 'date'],
            'doctorName'          => ['required', 'min:3'],
            'weightBeforeSurgery' => ['required'],
            'heightBeforeSurgery' => ['required'],
            'currentWeight'       => ['required'],
            'currentHeight'       => ['required'],
        ]);

        if ($validator->fails()) {
            return [
                'status' => FALSE,
                'data'   => $validator->errors()->toArray(),
            ];
        }

        // check if the user exists before
        $title = 'Registered User';
        $user  = User::where(([
            ['phone', $data['phone']],
            ['datOfSurgery', $data['datOfSurgery']],
            ['doctorName', $data['doctorName']],
        ]))->first();

        if ($user === NULL) {
            // create it
            User::create($data);
        } else {
            $title = 'Updated User';

            // update user
            $user->update($data);
        }

        return [
            'status' => TRUE,
            'title'  => $title,
        ];
    }

    /**
     * @param Request $request
     * @return array
     * @throws Exception
     * @author Ibrahim Sakr <ibrahim.sakr@tajawal.com>
     */
    public function mail(Request $request)
    {
        $body = $request->all();

        $results = $this->store($body);

        if (!$results['status']) {
            return response()->json([
                'status' => 'error',
                'data'   => $results['data'],
            ], 400);
        }

        $body['title'] = $results['title'];
        $mail          = new Newsletter($body);
        Mail::to(env('MAIL_TO', ''))->send($mail);

        return response()->json([
            'status' => 'ok',
            'data'   => [],
        ], 200);
    }

    /**
     * @param Request $request
     * @return int
     * @author Ibrahim Sakr <ibrahim.sakr@tajawal.com>
     */
    public function notification(Request $request): int
    {
        if ($request->get('hub_challenge', 0)) {
            Log::info('hub_challenge: ' . $request->get('hub_challenge', 0));
            return (int)$request->get('hub_challenge', 0);
        }

        Log::info(json_encode('XML content: ' . $request->getContent()));

        $xml = simplexml_load_string($request->getContent());

        $channelName = (string)$xml->entry->author->name ?? '';
        $videoUrl    = (string)$xml->entry->link->attributes()['href'] ?? '';
        $videoTitle  = (string)$xml->entry->title ?? '';

        Log::info(json_encode([
            'channel_name' => $channelName,
            'video_url'    => $videoUrl,
            'video_title'  => $videoTitle,
        ]));

        if (!$channelName || !$videoUrl || !$videoTitle) {
            return 1;
        }

        $payload = [
            'to'              => '/topics/' . env('FCM_TOPIC'),
            'priority'        => 'high',
            "mutable_content" => TRUE,
            "notification"    => [
                "title" => $channelName . ' :: New Video',
                "body"  => 'a new video published on ' . $channelName . ' with title ' . $videoTitle,
            ],
            'data'            => [
                'video_url' => $videoUrl,
            ],
        ];

        $headers = [
            'Authorization' => 'key=' . env('FCM_SERVER_KEY'),
            'Content-Type'  => 'application/json',
        ];

        Log::info(json_encode([
            'headers' => $headers,
            'payload' => $payload,
        ]));

        $this->sendHttp(env('FCM_BASE_URL'), $payload, $headers);
        return 1;
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
