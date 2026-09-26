<?php

namespace App\Http\Controllers;

use App\Support\Seo;
use App\Filament\Resources\BlogPostResource;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class BlogController extends Controller
{
    private const CARD_FIELDS = ['id', 'user_id', 'title', 'slug', 'excerpt', 'cover_image', 'category', 'published_at', 'views_count', 'is_featured', 'content'];

    public function index(Request $request)
    {
        $category = $request->query('category');
        $search   = trim((string) $request->query('search', ''));
        $tag      = $request->query('tag');

        $posts = BlogPost::published()
            ->with('author:id,name')
            ->when($category && $category !== 'all', fn ($q) => $q->where('category', $category))
            ->when($tag, fn ($q) => $q->whereJsonContains('tags', $tag))
            ->when($search !== '', fn ($q) => $q->where(fn ($s) => $s
                ->where('title', 'like', "%{$search}%")
                ->orWhere('excerpt', 'like', "%{$search}%")))
            ->orderByDesc('published_at')
            ->paginate(\App\Support\PerPage::resolve($request, 9), self::CARD_FIELDS)
            ->withQueryString();

        $posts->getCollection()->transform(fn ($p) => $this->card($p));

        $categories = BlogPost::published()
            ->selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->orderByDesc('count')
            ->get()
            ->map(fn ($c) => [
                'slug'  => $c->category,
                'name'  => BlogPostResource::CATEGORIES[$c->category] ?? Str::headline($c->category),
                'count' => $c->count,
            ]);

        $featured = ($category || $search !== '' || $tag || $request->integer('page', 1) > 1)
            ? null
            : BlogPost::published()->with('author:id,name')->where('is_featured', true)
                ->orderByDesc('published_at')->first(self::CARD_FIELDS);

        return Inertia::render('Blog/Index', [
            'posts'      => $posts,
            'featured'   => $featured ? $this->card($featured) : null,
            'categories' => $categories,
            'filters'    => $request->only(['category', 'search', 'tag']),
            'seo'        => Seo::make([
                'title'       => 'Blog — Tips, How-Tos & Stories from the Tech Desk',
                'description' => 'Practical tech tips, how-tos, deals and behind-the-scenes stories from the Git Infosys team.',
                'canonical'   => route('blog.index'),
                'type'        => 'website',
            ]),
        ]);
    }

    public function show(string $slug)
    {
        $post = BlogPost::published()->with('author:id,name')->where('slug', $slug)->firstOrFail();
        $post->increment('views_count');

        $related = BlogPost::published()->with('author:id,name')
            ->where('id', '!=', $post->id)
            ->where('category', $post->category)
            ->orderByDesc('published_at')->take(3)->get(self::CARD_FIELDS)
            ->map(fn ($p) => $this->card($p));

        $recent = BlogPost::published()->where('id', '!=', $post->id)
            ->orderByDesc('published_at')->take(5)
            ->get(['id', 'title', 'slug', 'cover_image', 'published_at']);

        $desc = $post->meta_description ?: ($post->excerpt ?: Str::limit(strip_tags($post->content), 155));
        $img  = $post->cover_image ? url('/storage/' . ltrim($post->cover_image, '/')) : null;

        return Inertia::render('Blog/Show', [
            'post'    => $post->toArray() + [
                'category_name' => BlogPostResource::CATEGORIES[$post->category] ?? Str::headline($post->category),
                'author_name'   => $post->author?->name,
            ],
            'related' => $related,
            'recent'  => $recent,
            'seo'     => Seo::for($post, [
                'title'       => $post->title,
                'description' => $desc,
                'image'       => $img,
                'image_alt'   => $post->title,
                'canonical'   => route('blog.show', $post->slug),
                'type'        => 'article',
                'json_ld'     => [
                    '@context'      => 'https://schema.org',
                    '@type'         => 'BlogPosting',
                    'headline'      => $post->title,
                    'description'   => $desc,
                    'image'         => $img,
                    'datePublished' => optional($post->published_at)->toIso8601String(),
                    'dateModified'  => $post->updated_at->toIso8601String(),
                    'author'        => ['@type' => 'Person', 'name' => $post->author?->name ?? config('app.name')],
                ],
            ]),
        ]);
    }

    /** Listing-card shape: no full body, just excerpt + reading time. */
    private function card(BlogPost $p): array
    {
        return [
            'id'           => $p->id,
            'title'        => $p->title,
            'slug'         => $p->slug,
            'excerpt'      => $p->excerpt ?: Str::limit(strip_tags((string) $p->content), 140),
            'cover_image'  => $p->cover_image,
            'category'     => $p->category,
            'category_name' => BlogPostResource::CATEGORIES[$p->category] ?? Str::headline($p->category),
            'published_at' => optional($p->published_at)->toIso8601String(),
            'views_count'  => $p->views_count,
            'reading_time' => $p->reading_time,
            'author'       => $p->author?->name,
        ];
    }
}
