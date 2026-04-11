<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Contract\Auth;

class ChatController extends Controller
{
    protected string $model = 'gemini-2.5-flash';
    protected string $apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/';

    public function __construct(protected Database $database, protected Auth $auth) {}

    public function send(Request $request)
    {
        $message  = $request->input('message');
        $userName = session('user_fullname', 'there');
        $goals    = session('goals', [ // ← ADDED
            'calories' => 2000,
            'protein'  => 150,
            'carbs'    => 200,
            'fat'      => 65,
        ]);

        $systemPrompt = "You are NutriBot 🍽️, a fun, friendly, and knowledgeable recipe and nutrition assistant for WellCook app. Your personality is warm, encouraging, and a little playful — like a foodie best friend who happens to know a lot about nutrition!

        The user's name is: {$userName}. Address them by their first name occasionally to make it feel personal and friendly.

        Your rules:
        - Only answer questions about food, recipes, cooking, nutrition, meal planning, and healthy eating
        - If asked about anything unrelated to food or nutrition, politely redirect the conversation back to food topics with a fun response
        - Use emojis occasionally to keep things fun and engaging 🥗🔥💪
        - Keep responses concise but informative — no walls of text
        - Give practical, actionable advice
        - Be encouraging when users talk about health goals
        - When suggesting recipes, always mention approximate calories or key nutrients if possible
        - Address the user in a friendly, conversational tone
        - Users may ask you for approximate macros (calories, protein, carbs, fat) of Filipino or other local meals to help them log their meals manually. When asked, provide a helpful estimate in this format:
        🔥 Calories: ~XXX kcal
        💪 Protein: ~XXg
        🌾 Carbs: ~XXg
        💧 Fat: ~XXg
        Always remind them these are estimates and can vary based on cooking method and portion size.
        - When the user wants to search for a recipe, use the searchRecipe function.
        - When the user wants to log a meal, use the logMeal function. Always estimate nutrition if not provided.
        - When the user wants to change their name, use the updateName function.
        - When the user wants to set or update their daily nutrition goals, use the updateGoals function.
        - When the user wants to see today's nutrition progress or asks for a daily summary, use the getDailySummary function.";


$tools = [
            [
                'functionDeclarations' => [
                    [
                        'name'        => 'searchRecipe',
                        'description' => 'Search for recipes by name or ingredient from TheMealDB',
                        'parameters'  => [
                            'type'       => 'object',
                            'properties' => [
                                'query' => [
                                    'type'        => 'string',
                                    'description' => 'The recipe name or ingredient to search for',
                                ],
                            ],
                            'required' => ['query'],
                        ],
                    ],
                    [
                        'name'        => 'logMeal',
                        'description' => 'Log a meal to the user\'s meal log with nutrition information',
                        'parameters'  => [
                            'type'       => 'object',
                            'properties' => [
                                'name'      => ['type' => 'string', 'description' => 'Name of the meal'],
                                'serving'   => ['type' => 'string', 'description' => 'Serving size e.g. 1 cup, 100g'],
                                'meal_type' => ['type' => 'string', 'description' => 'Breakfast, Lunch, Dinner, or Snack'],
                                'calories'  => ['type' => 'number', 'description' => 'Estimated calories'],
                                'protein'   => ['type' => 'number', 'description' => 'Estimated protein in grams'],
                                'carbs'     => ['type' => 'number', 'description' => 'Estimated carbs in grams'],
                                'fat'       => ['type' => 'number', 'description' => 'Estimated fat in grams'],
                            ],
                            'required' => ['name', 'serving', 'calories', 'protein', 'carbs', 'fat'],
                        ],
                    ],
                    [
                        'name'        => 'updateName',
                        'description' => 'Update the user\'s display name',
                        'parameters'  => [
                            'type'       => 'object',
                            'properties' => [
                                'name' => [
                                    'type'        => 'string',
                                    'description' => 'The new display name for the user',
                                ],
                            ],
                            'required' => ['name'],
                        ],
                    ],
                    [
                        'name'        => 'updateGoals',
                        'description' => 'Update the user\'s daily nutrition goals',
                        'parameters'  => [
                            'type'       => 'object',
                            'properties' => [
                                'calories' => ['type' => 'number', 'description' => 'Daily calorie goal in kcal'],
                                'protein'  => ['type' => 'number', 'description' => 'Daily protein goal in grams'],
                                'carbs'    => ['type' => 'number', 'description' => 'Daily carbs goal in grams'],
                                'fat'      => ['type' => 'number', 'description' => 'Daily fat goal in grams'],
                            ],
                            'required' => ['calories', 'protein', 'carbs', 'fat'],
                        ],
                    ],
                    [
                        'name'        => 'getDailySummary',
                        'description' => 'Get the user\'s total nutrition intake for today and compare it with daily goals',
                        'parameters'  => [
                            'type'       => 'object',
                            'properties' => new \stdClass(),
                        ],
                    ],
                ],
            ],
        ];

        // First Gemini call
        $response = Http::post(
            $this->apiUrl . $this->model . ':generateContent?key=' . env('GEMINI_API_KEY'),
            [
                'system_instruction' => ['parts' => [['text' => $systemPrompt]]],
                'contents'           => [['role' => 'user', 'parts' => [['text' => $message]]]],
                'tools'              => $tools,
            ]
        );

        $data   = $response->json();
        $status = $response->status();

        if ($status === 429 || ($data['error']['code'] ?? 0) === 429) {
            return response()->json([
                'reply' => '⏳ Oops! NutriBot is taking a quick breather — I\'ve hit my rate limit. Please wait a moment and try again! 🙏'
            ]);
        }

        if (isset($data['error'])) {
            return response()->json([
                'reply' => '😅 Something went wrong on my end. Please try again in a moment!'
            ]);
        }

        $candidate = $data['candidates'][0]['content'] ?? null;
        $parts     = $candidate['parts'] ?? [];

        // Check if Gemini wants to call a function
        foreach ($parts as $part) {
            if (!isset($part['functionCall'])) continue;

            $funcName = $part['functionCall']['name'];
            $args     = $part['functionCall']['args'];

            // Execute the function
            if ($funcName === 'searchRecipe') {
                $result = $this->searchRecipe($args['query']);
            } elseif ($funcName === 'logMeal') {
                $result = $this->logMeal($args);
            } elseif ($funcName === 'updateName') {
                $result = $this->updateName($args['name']);
            } elseif ($funcName === 'updateGoals') {
                $result = $this->updateGoals($args);
            } elseif ($funcName === 'getDailySummary') {
                $result = $this->getDailySummary($message);
            } else {
                continue;
            }

            // Send result back to Gemini — same model
            $secondResponse = Http::post(
                $this->apiUrl . $this->model . ':generateContent?key=' . env('GEMINI_API_KEY'),
                [
                    'system_instruction' => ['parts' => [['text' => $systemPrompt]]],
                    'contents'           => [
                        ['role' => 'user',  'parts' => [['text' => $message]]],
                        ['role' => 'model', 'parts' => $parts],
                        ['role' => 'user',  'parts' => [[
                            'functionResponse' => [
                                'name'     => $funcName,
                                'response' => ['result' => json_encode($result)],
                            ],
                        ]]],
                    ],
                    'tools' => $tools,
                ]
            );

            $secondData  = $secondResponse->json();
            $secondParts = $secondData['candidates'][0]['content']['parts'] ?? [];

            $reply = $result['message'] ?? '✅ Done successfully!';
            foreach ($secondParts as $secondPart) {
                if (isset($secondPart['text'])) {
                    $reply = $secondPart['text'];
                    break;
                }
            }
            return response()->json([
                'reply'         => $reply,
                'mealLogged'    => $funcName === 'logMeal'         && ($result['success'] ?? false),
                'nameUpdated'   => $funcName === 'updateName'      && ($result['success'] ?? false),
                'goalsUpdated'  => $funcName === 'updateGoals'     && ($result['success'] ?? false),
                'dailySummary'  => $funcName === 'getDailySummary' && ($result['success'] ?? false),
            ]);
        }

        // No function call — regular text reply
        $reply = '😅 Sorry, I could not process your request. Please try again!';
        foreach ($parts as $part) {
            if (isset($part['text'])) {
                $reply = $part['text'];
                break;
            }
        }

        return response()->json(['reply' => $reply]);
    }

    private function searchRecipe(string $query): array
    {
        $response = Http::get('https://www.themealdb.com/api/json/v1/1/search.php', [
            's' => $query,
        ]);

        $meals = $response->json()['meals'] ?? [];

        if (empty($meals)) {
            return ['found' => false, 'message' => 'No recipes found for: ' . $query];
        }

        return [
            'found'   => true,
            'recipes' => collect($meals)->take(5)->map(fn($meal) => [
                'name'     => $meal['strMeal'],
                'category' => $meal['strCategory'],
                'area'     => $meal['strArea'],
                'url'      => route('recipe.show', $meal['idMeal']),
            ])->values()->all(),
        ];
    }

    private function logMeal(array $args): array
    {
        $uid   = session('firebase_uid');
        $today = now()->toDateString();

        try {
            $this->database
                ->getReference('meal_logs/' . $uid . '/' . $today)
                ->push([
                    'name'      => $args['name'],
                    'serving'   => $args['serving']   ?? '1 serving',
                    'meal_type' => $args['meal_type'] ?? 'Lunch',
                    'calories'  => (float) $args['calories'],
                    'protein'   => (float) $args['protein'],
                    'carbs'     => (float) $args['carbs'],
                    'fat'       => (float) $args['fat'],
                    'logged_at' => now()->toDateTimeString(),
                ]);

            return ['success' => true, 'message' => $args['name'] . ' has been logged successfully!'];

        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    private function updateName(string $name): array
    {
        $uid = session('firebase_uid');

        try {
            $this->auth->updateUser($uid, [
                'displayName' => $name,
            ]);

            $this->database
                ->getReference('users/' . $uid)
                ->update(['fullname' => $name]);

            session(['user_fullname' => $name]);

            return ['success' => true, 'message' => 'Name updated to ' . $name . ' successfully!'];

        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    private function updateGoals(array $args): array
    {
        $uid = session('firebase_uid');

        try {
            $goals = [
                'calories' => (float) $args['calories'],
                'protein'  => (float) $args['protein'],
                'carbs'    => (float) $args['carbs'],
                'fat'      => (float) $args['fat'],
            ];

            $this->database
                ->getReference('users/' . $uid . '/goals')
                ->set($goals);

            session(['goals' => $goals]);

            return ['success' => true, 'message' => 'Daily goals updated successfully!', 'goals' => $goals];

        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    private function getDailySummary(string $userMessage = ''): array
    {
        $uid   = session('firebase_uid');
        $today = now()->toDateString();
        $goals = session('goals', [
            'calories' => 2000,
            'protein'  => 150,
            'carbs'    => 200,
            'fat'      => 65,
        ]);

        try {
            $snapshot = $this->database
                ->getReference('meal_logs/' . $uid . '/' . $today)
                ->getValue();

            $meals = $snapshot ? array_values($snapshot) : [];

            $totals = [
                'calories' => 0,
                'protein'  => 0,
                'carbs'    => 0,
                'fat'      => 0,
            ];

            foreach ($meals as $meal) {
                $totals['calories'] += (float) ($meal['calories'] ?? 0);
                $totals['protein']  += (float) ($meal['protein'] ?? 0);
                $totals['carbs']    += (float) ($meal['carbs'] ?? 0);
                $totals['fat']      += (float) ($meal['fat'] ?? 0);
            }

            $remaining = [
                'calories' => round((float) $goals['calories'] - $totals['calories'], 1),
                'protein'  => round((float) $goals['protein'] - $totals['protein'], 1),
                'carbs'    => round((float) $goals['carbs'] - $totals['carbs'], 1),
                'fat'      => round((float) $goals['fat'] - $totals['fat'], 1),
            ];

            $lowerMessage = strtolower($userMessage);

            if (str_contains($lowerMessage, 'calories') && str_contains($lowerMessage, 'left')) {
                $replyMessage = "You have {$remaining['calories']} kcal left for today 🔥";
            } elseif (str_contains($lowerMessage, 'protein') && str_contains($lowerMessage, 'left')) {
                $replyMessage = "You have {$remaining['protein']}g of protein left for today 💪";
            } elseif (str_contains($lowerMessage, 'carbs') && str_contains($lowerMessage, 'left')) {
                $replyMessage = "You have {$remaining['carbs']}g of carbs left for today 🌾";
            } elseif (str_contains($lowerMessage, 'fat') && str_contains($lowerMessage, 'left')) {
                $replyMessage = "You have {$remaining['fat']}g of fat left for today 💧";
            } elseif (str_contains($lowerMessage, 'calories')) {
                $replyMessage = "You’ve consumed " . round($totals['calories'], 1) . " kcal today out of {$goals['calories']} kcal 🔥";
            } else {
                $replyMessage = "Here’s your progress for today:\n" .
                    "🔥 Calories: " . round($totals['calories'], 1) . " / " . (float) $goals['calories'] . " kcal\n" .
                    "💪 Protein: " . round($totals['protein'], 1) . " / " . (float) $goals['protein'] . " g\n" .
                    "🌾 Carbs: " . round($totals['carbs'], 1) . " / " . (float) $goals['carbs'] . " g\n" .
                    "💧 Fat: " . round($totals['fat'], 1) . " / " . (float) $goals['fat'] . " g\n" .
                    "🍽️ Meals logged: " . count($meals);
            }

            return [
                'success' => true,
                'date'    => $today,
                'meals'   => count($meals),
                'totals'  => [
                    'calories' => round($totals['calories'], 1),
                    'protein'  => round($totals['protein'], 1),
                    'carbs'    => round($totals['carbs'], 1),
                    'fat'      => round($totals['fat'], 1),
                ],
                'goals'   => [
                    'calories' => (float) $goals['calories'],
                    'protein'  => (float) $goals['protein'],
                    'carbs'    => (float) $goals['carbs'],
                    'fat'      => (float) $goals['fat'],
                ],
                'remaining' => $remaining,
                'message'   => $replyMessage,
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}