<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(Request $request): View
    {
        $categories = collect([
            ['key' => 'movies', 'label' => __('Movies'), 'icon' => 'bi-film', 'description' => __('Trailers, reviews and more'), 'tone' => 'slate'],
            ['key' => 'tv-shows', 'label' => __('TV Shows'), 'icon' => 'bi-tv', 'description' => __('The best series and new releases'), 'tone' => 'violet'],
            ['key' => 'games', 'label' => __('Games'), 'icon' => 'bi-controller', 'description' => __('Trailers, news and curiosities'), 'tone' => 'blue'],
            ['key' => 'curiosities', 'label' => __('Curiosities'), 'icon' => 'bi-lightbulb', 'description' => __('Interesting facts and secrets'), 'tone' => 'amber'],
            ['key' => 'real-stories', 'label' => __('Real Stories'), 'icon' => 'bi-book', 'description' => __('Movies and games inspired by true events'), 'tone' => 'steel'],
            ['key' => 'reviews', 'label' => __('Reviews'), 'icon' => 'bi-file-earmark-text', 'description' => __('In-depth analysis and opinions'), 'tone' => 'crimson'],
        ]);

        $activeCategory = $request->string('category')->lower()->value();

        if (! $categories->contains('key', $activeCategory)) {
            $activeCategory = null;
        }

        $videoUrl = 'https://www.youtube-nocookie.com/embed/M7lc1UVf-VE?rel=0';

        $featuredItem = [
            'title' => 'The Night Signal',
            'type' => 'tv-shows',
            'type_label' => __('TV Show'),
            'subtype_label' => __('Trailer'),
            'excerpt' => __('A mysterious signal changes the routine of a city after dark.'),
            'likes_count' => '2.4K',
            'comments_count' => '320',
            'video_embed_url' => $videoUrl,
        ];

        $latestItems = collect([
            $this->media('movies', __('Movie'), 'Crimson Arena | Official Trailer', __('An explosive rivalry takes center stage.'), '2:31', '1.8K', '240', 'poster-01.webp', 'trailers', $videoUrl),
            $this->media('games', __('Game'), 'Neon Streets VI | Trailer 1', __('A first look at a sprawling neon city.'), '1:30', '2.6K', '420', 'poster-02.webp', 'trailers', $videoUrl),
            $this->media('tv-shows', __('TV Show'), 'Afterlight Season 2 | Official Trailer', __('A new chapter begins beyond the last safe zone.'), '2:24', '2.1K', '310', 'poster-03.webp', 'trailers', $videoUrl),
            $this->media('movies', __('Movie'), 'Red Dunes | Part Two', __('The next chapter of an epic desert journey.'), '2:37', '1.9K', '280', 'poster-04.webp', 'trailers', $videoUrl),
        ]);

        $popularItems = collect([
            $this->media('movies', __('Movie'), 'Harlequin: Final Act | Official Trailer', null, '2:25', '2.3K', '410', 'poster-05.webp', 'trailers', $videoUrl),
            $this->media('games', __('Game'), 'The Hunter IV | Cinematic Trailer', null, '3:12', '1.7K', '260', 'poster-06.webp', 'trailers', $videoUrl),
            $this->media('movies', __('Movie'), 'Mercenaries United | Official Trailer', null, '2:38', '2.0K', '390', 'poster-07.webp', 'trailers', $videoUrl),
            $this->media('tv-shows', __('TV Show'), 'House of Embers S2 | Official Trailer', null, '2:46', '1.6K', '310', 'poster-08.webp', 'trailers', $videoUrl),
            $this->media('games', __('Game'), 'Shadow Creed | World Premiere', null, '2:33', '1.5K', '220', 'poster-09.webp', 'trailers', $videoUrl),
        ]);

        $trendingItems = collect([
            ['title' => 'Crimson Arena II', 'subtitle' => __('Official Trailer'), 'views' => '185K', 'image_url' => asset('images/demo/poster-01.webp')],
            ['title' => 'Neon Streets VI', 'subtitle' => __('Trailer'), 'views' => '142K', 'image_url' => asset('images/demo/poster-02.webp')],
            ['title' => 'Afterlight S2', 'subtitle' => __('Trailer'), 'views' => '128K', 'image_url' => asset('images/demo/poster-03.webp')],
            ['title' => 'Blockworld Movie', 'subtitle' => __('Official Trailer'), 'views' => '110K', 'image_url' => asset('images/demo/poster-04.webp')],
            ['title' => 'Harlequin: Final Act', 'subtitle' => __('Trailer'), 'views' => '98K', 'image_url' => asset('images/demo/poster-05.webp')],
        ]);

        if ($activeCategory) {
            $latestItems = $this->filterByCategory($latestItems, $activeCategory);
            $popularItems = $this->filterByCategory($popularItems, $activeCategory);
        }

        return view('home', compact(
            'categories',
            'activeCategory',
            'featuredItem',
            'latestItems',
            'popularItems',
            'trendingItems',
        ));
    }

    private function media(
        string $type,
        string $typeLabel,
        string $title,
        ?string $excerpt,
        string $duration,
        string $likes,
        string $comments,
        string $image,
        string $contentKind,
        string $videoUrl,
    ): array {
        return [
            'type' => $type,
            'type_label' => $typeLabel,
            'title' => $title,
            'excerpt' => $excerpt,
            'duration' => $duration,
            'likes_count' => $likes,
            'comments_count' => $comments,
            'image_url' => asset('images/demo/'.$image),
            'content_kind' => $contentKind,
            'video_embed_url' => $videoUrl,
        ];
    }

    private function filterByCategory(Collection $items, string $activeCategory): Collection
    {
        return $items->where('type', $activeCategory)->values();
    }
}
