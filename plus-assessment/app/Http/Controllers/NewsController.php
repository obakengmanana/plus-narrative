<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class NewsController extends Controller
{
    public function getNews()
    {
        $client = new Client();

        try {
            $response = $client->request('POST', 'https://google-api31.p.rapidapi.com/', [
                'body' => '{
                    "text": "South Africa",
                    "region": "wt-wt",
                    "max_results": 10
                }',
                'headers' => [
                    'X-RapidAPI-Host' => 'google-api31.p.rapidapi.com',
                    'X-RapidAPI-Key' => 'd7d1ce6247mshb733d87dc1d39f3p19707bjsnb192c896fa56',
                    'content-type' => 'application/json',
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            // Assuming you have a view named 'news' in the 'resources/views' directory
            return view('news', ['data' => $data]);
        } catch (\Exception $e) {
            // Handle errors, you might want to log or return an error view
            return view('error', ['message' => $e->getMessage()]);
        }
    }
}
