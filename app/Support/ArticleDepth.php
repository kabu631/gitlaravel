<?php

namespace App\Support;

use App\Models\Gadget;
use Illuminate\Support\Str;

/**
 * Hand-written long-form text for reviews, news, guides and blog posts, kept in database/seeders/data/depth/*.txt:
 *
 *     === article-slug
 *     <h2>…</h2><p>…</p>
 *     [[specs:device-slug]]      specification table from the live catalogue
 *     [[rivals:device-slug]]     similar devices around the same Nepal price
 *     [[models:slug, slug, …]]   the named devices side by side, with current prices
 *     [[variants:device-slug]]   every version of one device with its current price
 *
 * A slug may appear more than once; later blocks are added below earlier ones.
 * The text is added after the article's short version. A marker keeps re-seeding from adding it twice.
 */
class ArticleDepth
{
    private const MARK = '<!--depth-->';

    /** @return array<string, array{html: string, replace: bool}> keyed by slug. "=== slug replace" replaces the old text. */
    public static function all(): array
    {
        $out = [];
        foreach (glob(database_path('seeders/data/depth/*.txt')) ?: [] as $file) {
            foreach (preg_split('/^=== /m', (string) file_get_contents($file)) as $chunk) {
                [$slug, $html] = array_pad(explode("\n", str_replace("\r\n", "\n", $chunk), 2), 2, '');
                [$slug, $mode] = array_pad(explode(' ', trim($slug), 2), 2, '');
                if ($slug !== '' && trim($html) !== '') {
                    $out[$slug] = [
                        'html' => trim(($out[$slug]['html'] ?? '') . "
" . trim($html)),
                        'replace' => $mode === 'replace' || ($out[$slug]['replace'] ?? false),
                    ];
                }
            }
        }

        return $out;
    }

    /** @param array{html: string, replace: bool} $depth */
    public static function apply(string $content, array $depth): string
    {
        $base = $depth['replace'] ? '' : rtrim(Str::before($content, self::MARK)) . "\n";

        return $base . self::MARK . "\n" . self::expand($depth['html']);
    }

    /** Fill in the [[…]] placeholders; text without any is returned unchanged. */
    public static function expand(string $html): string
    {
        $html = preg_replace_callback('/\[\[models:([a-z0-9-,\s]+)\]\]/', fn ($m) => self::models(preg_split('/[,\s]+/', trim($m[1]))), $html);

        return preg_replace_callback('/\[\[(specs|rivals|variants):([a-z0-9-]+)\]\]/', function ($m) {
            $gadget = Gadget::with(['specs', 'category'])->firstWhere('slug', $m[2]);

            return $gadget ? self::{$m[1]}($gadget) : '';
        }, $html);
    }

    private static function specs(Gadget $g): string
    {
        $s = $g->specs;
        if (! $s) {
            return '';
        }
        $rows = [];
        foreach (['display' => 'Display', 'processor' => 'Processor', 'ram' => 'Memory', 'storage' => 'Storage', 'camera' => 'Cameras', 'battery' => 'Battery', 'os' => 'Software', 'connectivity' => 'Connectivity', 'weight' => 'Weight', 'dimensions' => 'Size'] as $field => $label) {
            if ($value = self::known($s->{$field} ?? null)) {
                $rows[$label] = $value;
            }
        }

        return $rows ? self::table(['Specification', $g->name], collect($rows)->map(fn ($v, $k) => [e($k), e(Str::limit((string) $v, 160))])->values()->all()) : '';
    }

    /** @param list<string> $slugs */
    private static function models(array $slugs): string
    {
        $found = Gadget::with('specs')->whereIn('slug', $slugs)->get()->keyBy('slug');
        $rows = collect($slugs)->map(fn ($s) => $found->get($s))->filter();
        if ($rows->isEmpty()) {
            return '';
        }
        $columns = collect(['display' => 'Display', 'processor' => 'Chip', 'ram' => 'Memory', 'battery' => 'Battery', 'storage' => 'Storage', 'connectivity' => 'Connection'])
            ->filter(fn ($label, $field) => $rows->filter(fn ($g) => self::known($g->specs?->{$field}))->count() > $rows->count() / 2)->take(4);

        return self::table(['Model', 'Price in Nepal', ...$columns->values()], $rows->map(fn ($g) => [
            '<a href="/products/' . e($g->slug) . '">' . e($g->name) . '</a>',
            $g->price > 0 ? 'Rs&nbsp;' . self::money((float) $g->price) : '–',
            ...$columns->keys()->map(fn ($f) => e(Str::limit(self::known($g->specs?->{$f}) ?? '–', 45)))->all(),
        ])->all());
    }

    private static function variants(Gadget $g): string
    {
        $variants = $g->productVariants()->where('price', '>', 0)->get();
        if ($variants->count() < 2) {
            return '';
        }

        return self::table(['Version', 'Price in Nepal'], $variants->map(fn ($v) => [
            e(implode(' / ', array_filter([$v->ram, $v->storage, $v->edition, $v->size]))),
            'Rs&nbsp;' . self::money((float) $v->price),
        ])->all());
    }

    private static function rivals(Gadget $g): string
    {
        $price = (float) $g->price;
        if ($price <= 0) {
            return '';
        }
        $rivals = Gadget::with('specs')
            ->where('category_id', $g->category_id)->where('id', '!=', $g->id)
            ->where('release_status', Gadget::STATUS_AVAILABLE)->where('price_is_estimate', false)
            ->whereNull('out_of_stock_at')
            ->whereBetween('price', [$price * 0.8, $price * 1.25])
            ->where('name', 'not like', '%' . Str::words($g->name, 3, '') . '%')
            ->orderByRaw('ABS(price - ?)', [$price])->limit(12)->get()
            ->unique('brand_id')->take(4);
        if ($rivals->count() < 2) {
            return '';
        }
        $row = fn (Gadget $x, bool $self = false) => [
            $self ? '<strong>' . e($x->name) . '</strong>' : '<a href="/products/' . e($x->slug) . '">' . e($x->name) . '</a>',
            'Rs&nbsp;' . self::money((float) $x->price),
            e(Str::limit(self::known($x->specs?->processor) ?? self::known($x->specs?->display) ?? '–', 60)),
            e(Str::limit(self::known($x->specs?->battery) ?? self::known($x->specs?->ram) ?? '–', 50)),
        ];

        return self::table(['Device', 'Price in Nepal', 'Chip or screen', 'Battery or memory'], collect([$row($g, true)])->merge($rivals->map(fn ($r) => $row($r)))->all());
    }

    /** Early imports stored "N/A" and a made-up processor name for devices without these fields. */
    private static function known(?string $value): ?string
    {
        return blank($value) || in_array(trim($value), ['N/A', 'n/a', '-', 'Integrated Processor V2'], true) ? null : $value;
    }

    private static function table(array $head, array $rows): string
    {
        return '<table><thead><tr>' . implode('', array_map(fn ($h) => '<th>' . e($h) . '</th>', $head)) . '</tr></thead><tbody>'
            . implode('', array_map(fn ($r) => '<tr>' . implode('', array_map(fn ($c) => "<td>{$c}</td>", $r)) . '</tr>', $rows))
            . '</tbody></table>';
    }

    private static function money(float $n): string
    {
        $s = (string) (int) round($n);
        if (strlen($s) <= 3) {
            return $s;
        }

        return preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', substr($s, 0, -3)) . ',' . substr($s, -3);
    }
}
