<?php

namespace App\Http\Controllers;

use App\Services\VigenereCipher;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Message;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Message::with(['messageFrom'])->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $message = $request->get('message');
        $keyword = $request->get('keyword');

        $cipher = new VigenereCipher();
        $encrypt = $cipher->encrypt($message, $keyword);
        $decrypt = $cipher->decrypt($encrypt, $keyword);

        $messageDB = new Message;
        $messageDB->message = $message;
        $messageDB->keyword = $keyword;
        $messageDB->encrypted = $encrypt;
        $messageDB->decrypted = $decrypt;
        $messageDB->from = Auth::user()->id;

        if($messageDB->save()) {
            return response()->json(array(
                'message' => 'success',
                'data' => $messageDB
            ));
        }

        return response()->json(array(
            'message' => 'failed',
            'data' => false
        ));
    }

    public function decode(Request $request) {
        $keyword = $request->get('keyword');
        $targetMessage = $request->get('messageId');

        $message = Message::find($targetMessage);

        $cipher = new VigenereCipher();
        $decrypt = $cipher->decrypt($message->encrypted, $keyword);
        if($decrypt == $message->decrypted) {
            return response()->json(array(
                'message' => 'success',
                'data' => $message
            ));
        }

        return response()->json(array(
            'message' => 'failed',
            'data' => false
        ));
    }
}
