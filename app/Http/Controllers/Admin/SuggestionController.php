<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Suggestion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SuggestionController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->string('status')->value();
        $suggestions = Suggestion::query()
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest()->paginate(20)->withQueryString();

        return view('admin.suggestions.index', compact('suggestions', 'status'));
    }

    public function update(Request $request, Suggestion $suggestion): RedirectResponse
    {
        $data = $request->validate(['status' => ['required', 'in:pending,reviewed,accepted,rejected']]);
        $suggestion->update($data);

        return back()->with('success', __('Suggestion updated.'));
    }

    public function destroy(Suggestion $suggestion): RedirectResponse
    {
        $suggestion->delete();
        return back()->with('success', __('Suggestion deleted.'));
    }
}
