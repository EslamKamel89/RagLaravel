<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HttpControlller extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $question  = $_GET['question_'] ?? null;

        $response = Http::post('http://13.48.184.6:6000/ask', [
            'question' => $question ?? 'من أنت؟',
        ]);

        // استخراج البيانات أو التعامل مع النتيجة
        if ($response->successful()) {
            return response()->json([
                'status' => 'success',
                'data' => $response->json(),
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => $response->body(),
        ], $response->status());
    }
    public function getAyat() {
        $question  = $_GET['question'] ?? null;
        $messageId  = $_GET['userid'] ?? null;
        $response = Http::withOptions([
            'connect_timeout' => 100, // المهلة لفتح الاتصال
            'timeout' => 240,         // المهلة الإجمالية للطلب
        ])->post('http://13.48.184.6:6000/search', [
            'userinput' => $question ?? 'من أنت؟',
        ]);


        // استخراج البيانات أو التعامل مع النتيجة
        if ($response->successful()) {
            return response()->json([
                'status' => 'success',
                'data' => $response->json(),
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => $response->body(),
        ], $response->status());
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        $response = Http::post('http://13.48.184.6:6000/ask', [
            'question' => $request->question,
        ]);

        // استخراج البيانات أو التعامل مع النتيجة
        if ($response->successful()) {
            return response()->json([
                'status' => 'success',
                'data' => $response->json(),
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => $response->body(),
        ], $response->status());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {
        //
    }
}
