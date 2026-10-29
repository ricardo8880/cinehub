<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Suggestion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SuggestionController extends Controller
{
    public function create(string $locale): View
    {
        return view('public.suggestion', [
            'navigationCategories' => Category::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request, string $locale): RedirectResponse
    {
        abort_if($request->filled('website'), 422);

        $data = $request->validate([
            'subject' => ['required', 'string', 'max:160'],
            'message' => ['required', 'string', 'max:5000'],
            'email' => ['nullable', 'email', 'max:255'],
        ]);

        Suggestion::create($data + ['status' => 'pending']);

        return back()->with('success', __('Suggestion sent successfully.'));
    }
}
