<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SmsController extends Controller
{
    public function sendSms(Request $request)
    {
        echo "hi";
exit;
        // $request->validate([
        //     'phone' => 'required|numeric',
        //     'message' => 'required|string',
        // ]);

        // $sid = env('TWILIO_SID');
        // $token = env('TWILIO_AUTH_TOKEN');
        // $twilioNumber = env('TWILIO_PHONE_NUMBER');

        // $client = new Client($sid, $token);

        // try {
        //     $client->messages->create(
        //         $request->input('phone'),
        //         [
        //             'from' => $twilioNumber,
        //             'body' => $request->input('message'),
        //         ]
        //     );

        //     return response()->json(['success' => 'SMS sent successfully!']);
        // } catch (\Exception $e) {
        //     return response()->json(['error' => 'Failed to send SMS: ' . $e->getMessage()], 500);
        // }
    }
}
