<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SiteSetting;
use Illuminate\View\View;

class BookController extends Controller
{
    public function __invoke(string $locale): View
    {
        return view('public.book', [
            'navigationCategories' => Category::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get(),
            'book' => [
                'title' => SiteSetting::valueFor('book_title'),
                'synopsis' => SiteSetting::valueFor('book_synopsis'),
                'cover_url' => SiteSetting::valueFor('book_cover_url'),
                'sample_url' => SiteSetting::valueFor('book_sample_url'),
                'purchase_url' => SiteSetting::valueFor('book_purchase_url'),
            ],
        ]);
    }
}
