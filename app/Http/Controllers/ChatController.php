<?php

namespace App\Http\Controllers;
use App\Models\Message;
use Illuminate\Http\Request;
use Inertia\Inertia;




class ChatController extends Controller
{
    public function index()
    {
        $messages = Message::with('user')->orderBy('id')->get();
        return Inertia::render('Chat/Index' ,[
            'messages'=> $messages
        ]);
    }


    public function store(Request $request){

            $request->validate([
                'content' => 'required|string|max:1000',
            ]);

            $message = Message::create([
                'user_id'=>auth()->id(),
                'content'=>$request->content,
            ]);

            return redirect()->route('chat.index');

    }


    public function update(Request $request, Message $message){


        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $message->update([
            'content'=> $request -> content,
        ]);


        return redirect()->route('chat.index');

    }


    public function destroy(Message $message){
        $message->delete();
        return redirect()->route('chat.index');
    }

}
