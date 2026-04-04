<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    public function send(Request $request)
    {
        $message = $request->input('message');

        $response = Http::post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-lite:generateContent?key=' . env('GEMINI_API_KEY'), [
            'contents' => [
                [
                    'parts' => [
                        [
                            'text' => "You are NutriBot 🍽️, a fun, friendly, and knowledgeable recipe and nutrition assistant for WellCook app. Your personality is warm, encouraging, and a little playful — like a foodie best friend who happens to know a lot about nutrition!

        Your rules:
        - Only answer questions about food, recipes, cooking, nutrition, meal planning, and healthy eating
        - If asked about anything unrelated to food or nutrition, politely redirect the conversation back to food topics with a fun response
        - Use emojis occasionally to keep things fun and engaging 🥗🔥💪
        - Keep responses concise but informative — no walls of text
        - Give practical, actionable advice
        - Be encouraging when users talk about health goals
        - When suggesting recipes, always mention approximate calories or key nutrients if possible
        - Address the user in a friendly, conversational tone
        - If user ask for a recommendation, ask first what categories the user wants, and its ethnicity

        User: " . $message
                        ]
                    ]
                ]
            ]
        ]);

        $data = $response->json();
        $status = $response->status();

        // Handle rate limit
        if ($status === 429) {
            return response()->json([
                'reply' => '⏳ Oops! NutriBot is taking a quick breather — I\'ve hit my rate limit. Please wait a moment and try again! 🙏'
            ]);
        }

        // Handle other API errors
        if (isset($data['error'])) {
            $errorCode = $data['error']['code'] ?? 0;

            if ($errorCode === 429) {
                return response()->json([
                    'reply' => '⏳ Oops! NutriBot is taking a quick breather — I\'ve hit my rate limit. Please wait a moment and try again! 🙏'
                ]);
            }

            return response()->json([
                'reply' => '😅 Something went wrong on my end. Please try again in a moment!'
            ]);
        }

        $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? '😅 Sorry, I could not process your request. Please try again!';

        return response()->json(['reply' => $reply]);

        
    }
}