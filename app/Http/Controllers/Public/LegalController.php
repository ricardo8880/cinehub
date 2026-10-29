<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\View\View;

class LegalController extends Controller
{
    public function show(string $locale, string $page): View
    {
        abort_unless(in_array($page, ['privacy', 'terms', 'cookies'], true), 404);

        return view("public.legal.$page", [
            'navigationCategories' => Category::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get(),
        ]);
    }
}
