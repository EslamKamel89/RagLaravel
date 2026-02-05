<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class RuleController extends Controller {
    protected string $externalApiUrl;
    protected $timeout;
    public function __construct() {
        $this->externalApiUrl = rtrim(config('externalapi.base_url'), '/');
        $this->timeout = config('externalapi.timeout');
    }
    public function index(Request $request) {
        $filter = trim($request->query('filter', ''));
        try {
            $rules = [];

            $response = Http::timeout(60)->get("{$this->externalApiUrl}/readAll");
            if ($response->successful()) {
                $rules = $response->json('data');
                $rules = $this->filter($filter, $rules);
            } else {
                session()->flash('error', 'Failed to fetch rules from the server.');
            }
        } catch (\Throwable $th) {
            $rules = [];
            session()->flash('error', 'Unable to connect to the rule service.');
        }
        //dd($rules);
        return view('admin.rules.index', ['rules' => $rules, 'filter' => $filter]);
    }
    public function create() {
        return view('admin.rules.create');
    }
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'rules' => 'required|string|max:255',
            'content' => 'required|string',
            'main_keyword' => 'required|array',
            'main_keyword.*' => 'string',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $response = Http::post("{$this->externalApiUrl}/create", [
                'rules' => $request->get('rules'),
                'content' => $request->get('content'),
                'main_keyword' => $request->get('main_keyword'),
            ]);
            if ($response->successful()) {
                return redirect()->route('rules.index')->with('success', 'Rule created successfully.');
            } else {
                $error = $response->json('message') ?? 'Unknown error.';
                return redirect()->back()->with('error', "Create failed: $error")->withInput();
            }
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Unable to connect to the rule service.')->withInput();
        }
    }
    public function edit(string $id) {
        try {
            $response = Http::timeout(60)->post("{$this->externalApiUrl}/readOne", ['id_' => $id]);
            if ($response->successful() && $response->json()) {
                return view('admin.rules.edit', ['rule' => $response->json('data')]);
            } else {
                return redirect()->route('rules.index')->with('error', 'Rule not found.');
            }
        } catch (\Throwable $th) {
            return redirect()->route('rules.index')->with('error', 'Unable to retrieve the rule.');
        }
    }
    public function update(Request $request, string $id) {
        $validator = Validator::make($request->all(), [
            'rules' => 'required|string|max:255',
            'content' => 'required|string',
            'main_keyword' => 'required|array',
            'main_keyword.*' => 'string',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {

            $response = Http::timeout(60)->patch("{$this->externalApiUrl}/update", [
                'id_' => $id,
                // "field_name" => "main_keyword",
                // 'value' => $request->get('main_keyword'),
                'Data' => [
                    'rules' => $request->get('rules'),
                    'content' => $request->get('content'),
                    'main_keyword' => $request->get('main_keyword'),
                ],
            ]);
            if ($response->successful()) {
                return redirect()->route('rules.index')->with('success', 'Rule updated successfully.');
            } else {
                $error = $response->json('message');
                return redirect()->back()->with('error', "Update failed: $error")->withInput();
            }
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Unable to connect to the rule service.')->withInput();
        }
    }
    public function destroy(string $id) {
        try {
            $response = Http::timeout(60)->delete("{$this->externalApiUrl}/delete", ['id_' => $id]);
            if ($response->successful()) {
                return redirect()->route('rules.index')->with('success', 'Rule deleted successfully.');
            } else {
                return redirect()->route('rules.index')->with('error', 'Failed to delete the rule.');
            }
        } catch (\Throwable $th) {
            return redirect()->route('rules.index')->with('error', 'Unable to delete the rule.');
        }
    }
    protected function filter(string $filter, array $rules): array {
        $filter = trim($filter);
        if ($filter === '') return $rules;
        $filterLower = mb_strtolower($filter);
        return collect($rules)
            ->filter(function ($rule) use ($filterLower) {
                $ruleName = mb_strtolower($rule['rules'] ?? '');
                if (str_contains($ruleName, $filterLower)) {
                    return true;
                }
                $keywords = is_array($rule['main_keyword'] ?? null)
                    ? $rule['main_keyword']
                    : [];
                foreach ($keywords as $keyword) {
                    if (str_contains(mb_strtolower($keyword), $filterLower)) {
                        return true;
                    }
                }
                return false;
            })
            ->values()
            ->all();
    }
}
