<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\ArticleImageManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ArticleImageController extends Controller
{
    public function store(Request $request, ArticleImageManager $images): JsonResponse
    {
        $locales = array_keys(config('localization.locales'));
        $validated = $request->validate([
            'locale' => ['required', 'string', Rule::in($locales)],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:6144'],
        ]);

        $images->cleanupOrphansForUser((int) $request->user()->id);
        $stored = $images->store($validated['image'], (int) $request->user()->id, $validated['locale']);

        return response()->json([
            'url' => $stored['url'],
        ]);
    }
}
