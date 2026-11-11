<?php

use App\Http\Controllers\Admin\ActivityLogController as AdminActivityLogController;
use App\Http\Controllers\Admin\ArticleImageController as AdminArticleImageController;
use App\Http\Controllers\Admin\BlogCategoryController as AdminBlogCategoryController;
use App\Http\Controllers\Admin\BlogGenreController as AdminBlogGenreController;
use App\Http\Controllers\Admin\AdSlotController as AdminAdSlotController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CommentController as AdminCommentController;
use App\Http\Controllers\Admin\ContactMessageController as AdminContactMessageController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\GenreController as AdminGenreController;
use App\Http\Controllers\Admin\MediaController as AdminMediaController;
use App\Http\Controllers\Admin\MetricsController as AdminMetricsController;
use App\Http\Controllers\Admin\ModerationTermController as AdminModerationTermController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\SiteSettingController as AdminSiteSettingController;
use App\Http\Controllers\Admin\SuggestionController as AdminSuggestionController;
use App\Http\Controllers\Admin\SearchTermController as AdminSearchTermController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Public\BlogController;
use App\Http\Controllers\Public\BookController;
use App\Http\Controllers\Public\CategoryController as PublicCategoryController;
use App\Http\Controllers\Public\CommentController as PublicCommentController;
use App\Http\Controllers\Public\CommentReactionController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\CookieConsentController;
use App\Http\Controllers\Public\LegalController;
use App\Http\Controllers\Public\ReactionController;
use App\Http\Controllers\Public\PostController as PublicPostController;
use App\Http\Controllers\Public\PageEngagementController;
use App\Http\Controllers\Public\SearchController;
use App\Http\Controllers\Public\SuggestionController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\Member\AuthController as MemberAuthController;
use App\Http\Controllers\Member\GoogleAuthController;
use App\Http\Controllers\Member\FeedController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
// Laravel's auth middleware uses this route when a guest follows a protected link.
Route::get('/login', fn () => redirect()->route('member.login', ['locale' => config('localization.default')]))->name('login');

Route::get('/', function () {
    return redirect()->route('home', [
        'locale' => config('localization.default'),
    ]);
})->name('root');

Route::prefix('admin')->name('admin.')->group(function (): void {
    Route::middleware('guest:admin')->group(function (): void {
        Route::get('/login', [AdminAuthController::class, 'create'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'store'])->middleware('throttle:5,1')->name('login.store');
        Route::get('/two-factor', [AdminAuthController::class, 'twoFactorForm'])->name('two-factor.form');
        Route::post('/two-factor', [AdminAuthController::class, 'verifyTwoFactor'])->middleware('throttle:5,1')->name('two-factor.verify');
    });

    Route::middleware(['auth:admin', 'admin', 'admin.session', 'admin.log'])->group(function (): void {
        Route::get('/', AdminDashboardController::class)->name('dashboard');
        Route::get('/metrics', AdminMetricsController::class)->name('metrics');
        Route::get('/metrics/report', [AdminMetricsController::class, 'export'])->name('metrics.report');
        Route::get('/metrics/comments-report', [AdminMetricsController::class, 'exportComments'])->name('metrics.comments-report');
        Route::get('/activity-logs', AdminActivityLogController::class)->name('logs');
        Route::get('/settings', [AdminSiteSettingController::class, 'edit'])->name('settings.edit');
        Route::put('/settings', [AdminSiteSettingController::class, 'update'])->name('settings.update');
        Route::resource('ad-slots', AdminAdSlotController::class)->parameters(['ad-slots' => 'adSlot'])->only(['index', 'store', 'update', 'destroy']);
        Route::resource('categories', AdminCategoryController::class)->except('show');
        Route::resource('genres', AdminGenreController::class)->except('show');
        Route::resource('blog-categories', AdminBlogCategoryController::class)->parameters(['blog-categories' => 'blog_category'])->except('show');
        Route::resource('blog-genres', AdminBlogGenreController::class)->parameters(['blog-genres' => 'blog_genre'])->except('show');
        Route::post('/posts/article-images', [AdminArticleImageController::class, 'store'])->name('posts.article-images.store');
        Route::resource('posts', AdminPostController::class)->except('show');
        Route::resource('comments', AdminCommentController::class)->only(['index', 'update', 'destroy']);
        Route::resource('moderation', AdminModerationTermController::class)->parameters(['moderation' => 'moderationTerm'])->only(['index', 'store', 'update', 'destroy']);
        Route::resource('suggestions', AdminSuggestionController::class)->only(['index', 'update', 'destroy']);
        Route::get('/search-terms', [AdminSearchTermController::class, 'index'])->name('search-terms.index');
        Route::delete('/search-terms/{searchTerm}', [AdminSearchTermController::class, 'destroy'])->name('search-terms.destroy');
        Route::resource('contacts', AdminContactMessageController::class)->only(['index', 'update', 'destroy']);
        Route::resource('posts.media', AdminMediaController::class)->except('show');
        Route::post('/logout', [AdminAuthController::class, 'destroy'])->name('logout');
    });
});

// Preserve old links, including their full paths and query parameters.
// Use 308 for non-GET methods so existing forms retain their request body.
Route::any('/{englishRegion}/{path?}', function (Request $request, string $englishRegion, ?string $path = null) {
    $target = '/en'.($path !== null && $path !== '' ? '/'.$path : '');

    if ($request->getQueryString()) {
        $target .= '?'.$request->getQueryString();
    }

    return redirect()->to($target, $request->isMethod('GET') || $request->isMethod('HEAD') ? 301 : 308);
})->where(['englishRegion' => 'en-us|en-gb|en-ca|en-au', 'path' => '.*']);

$localePattern = implode('|', array_map(
    static fn (string $locale): string => preg_quote($locale, '/'),
    array_unique(array_merge(['en'], array_keys(config('localization.locales')))),
));

Route::prefix('{locale}')
    ->where(['locale' => $localePattern])
    ->middleware('locale')
    ->group(function (): void {
        Route::get('/', HomeController::class)->name('home');
        Route::get('/account/login', [MemberAuthController::class, 'login'])->name('member.login');
        Route::post('/account/login', [MemberAuthController::class, 'authenticate'])->middleware('throttle:5,1')->name('member.login.store');
        Route::get('/account/register', [MemberAuthController::class, 'register'])->name('member.register');
        Route::post('/account/register', [MemberAuthController::class, 'create'])->middleware('throttle:4,10')->name('member.register.store');
        Route::get('/account/google', [GoogleAuthController::class, 'redirect'])->middleware('throttle:10,1')->name('member.google');
        Route::get('/account/google/callback', [GoogleAuthController::class, 'callback'])->middleware('throttle:10,1')->name('member.google.callback');
        Route::get('/account/forgot-password', [MemberAuthController::class, 'forgot'])->name('member.password.forgot');
        Route::post('/account/forgot-password', [MemberAuthController::class, 'sendReset'])->middleware('throttle:3,10')->name('member.password.email');
        Route::get('/account/reset-password/{token}', [MemberAuthController::class, 'resetForm'])->name('password.reset');
        Route::post('/account/reset-password', [MemberAuthController::class, 'reset'])->middleware('throttle:5,1')->name('member.password.update');
        Route::middleware('auth')->group(function (): void {
            Route::post('/account/logout', [MemberAuthController::class, 'logout'])->name('member.logout');
            Route::get('/for-you', [FeedController::class, 'forYou'])->name('member.for-you');
            Route::get('/favorites', [FeedController::class, 'favorites'])->name('member.favorites');
            Route::get('/interests', [FeedController::class, 'interests'])->name('member.interests');
            Route::put('/interests', [FeedController::class, 'updateInterests'])->middleware('throttle:10,1')->name('member.interests.update');
            Route::post('/favorites/{post}', [FeedController::class, 'toggleFavorite'])->middleware('throttle:30,1')->name('member.favorites.toggle');
            Route::post('/comments/preview', [PublicCommentController::class, 'preview'])->middleware('throttle:120,1')->name('member.comments.preview');
        });

        Route::get('/search', SearchController::class)->middleware('throttle:30,1')->name('search');
        Route::post('/comments/{comment}/like', CommentReactionController::class)
            ->middleware(['auth', 'throttle:30,1'])->name('comments.like');
        Route::get('/search/suggestions', [SearchController::class, 'suggestions'])->middleware('throttle:60,1')->name('search.suggestions');
        Route::post('/search/selection', [SearchController::class, 'selection'])->middleware('throttle:30,1')->name('search.selection');
        Route::get('/contact', [ContactController::class, 'create'])->name('contact');
        Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:3,10')->name('contact.store');
        Route::get('/suggest', [SuggestionController::class, 'create'])->name('suggestions.create');
        Route::post('/suggest', [SuggestionController::class, 'store'])->middleware('throttle:3,10')->name('suggestions.store');
        Route::get('/legal/{page}', [LegalController::class, 'show'])->where('page', 'privacy|terms|cookies')->name('legal.show');
        Route::post('/privacy/consent', [CookieConsentController::class, 'update'])->middleware('throttle:10,1')->name('privacy.consent');
        Route::get('/book', BookController::class)->name('book');
        Route::post('/page-engagement', PageEngagementController::class)->middleware('throttle:120,1')->name('page-engagement.store');
        Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
        Route::post('/blog/{slug}/comments', [PublicCommentController::class, 'storeBlog'])
            ->middleware(['auth', 'throttle:5,1'])
            ->where('slug', '[a-z0-9-]+')
            ->name('blog.comments.store');
        Route::post('/blog/{slug}/like', [ReactionController::class, 'toggleBlog'])
            ->middleware(['auth', 'throttle:30,1'])
            ->where('slug', '[a-z0-9-]+')
            ->name('blog.reactions.like');
        Route::get('/blog/{slug}', [BlogController::class, 'show'])
            ->where('slug', '[a-z0-9-]+')
            ->name('blog.show');
        Route::get('/{category}', PublicCategoryController::class)
            ->where('category', '[a-z0-9-]+')
            ->name('categories.show');
        Route::post('/{category}/{slug}/comments', [PublicCommentController::class, 'store'])
            ->middleware(['auth', 'throttle:5,1'])
            ->where(['category' => '[a-z0-9-]+', 'slug' => '[a-z0-9-]+'])
            ->name('comments.store');
        Route::post('/{category}/{slug}/like', [ReactionController::class, 'toggle'])
            ->middleware(['auth', 'throttle:30,1'])
            ->where(['category' => '[a-z0-9-]+', 'slug' => '[a-z0-9-]+'])
            ->name('reactions.like');
        Route::get('/{category}/{slug}', PublicPostController::class)
            ->where(['category' => '[a-z0-9-]+', 'slug' => '[a-z0-9-]+'])
            ->name('posts.show');
    });
