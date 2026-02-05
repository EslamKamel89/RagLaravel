<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProxyController extends Controller {
    protected string $externalApiUrl;
    protected int $timeout = 60;
    public function __construct() {
        $this->externalApiUrl = env('EXTERNAL_API_BASE_URL');
    }
    public function proxy(Request $request) {
        $validated = $request->validate([
            'method' => 'required|in:GET,POST,PUT,PATCH,DELETE',
            'path' =>  'required|string',
            'body' => 'nullable|array',
        ]);
        $url = $this->externalApiUrl . '/' . $validated['path'];
        $method = $validated['method'];
        $body = $validated['body'] ?? [];
        $headers = ['Content-Type' => 'application/json'];
        $response = null;
        // dd($body);
        try {
            if ($method === 'GET') {
                $response = Http::timeout($this->timeout)
                    ->withHeaders($headers)
                    ->get($url, $body);
            } else {
                $response = Http::timeout($this->timeout)
                    ->withHeaders($headers)
                    ->withBody(json_encode($body), 'application/json')
                    ->send($method, $url);
            }
            return response($response->body(), $response->status())
                ->withHeaders($response->headers());
        } catch (\Throwable $th) {
            return response()->json(['error' => "Proxy request failed: {$th->getMessage()}"], 500);
        }
    }
}
