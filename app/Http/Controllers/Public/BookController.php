<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SiteSetting;
use App\Support\Seo;
use Illuminate\View\View;

class BookController extends Controller
{
    public function __invoke(string $locale): View
    {
        $book = SiteSetting::bookFor($locale);
        abort_unless(filled($book['title']), 404);

        $alternateUrls = [];
        foreach (Seo::indexableLocales() as $candidate) {
            if (filled(SiteSetting::valueFor(SiteSetting::bookKey('title', $candidate)))) {
                $alternateUrls[$candidate] = route('book', ['locale' => $candidate]);
            }
        }

        return view('public.book', [
            'alternateUrls' => $alternateUrls,
            'navigationCategories' => Category::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get(),
            'book' => $book,
        ]);
    }
}
