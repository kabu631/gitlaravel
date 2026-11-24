<?php

namespace App\Http\Controllers;

use App\Support\Seo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class PCBuilderController extends Controller
{
    /** Options offered by the builder form; the same lists validate the request. */
    public const MONITORS  = ['1080p 60–75Hz', '1080p 144Hz+', '1440p 144Hz+', '4K', 'Not sure yet'];
    public const PLATFORMS = ['Any', 'AMD', 'Intel'];
    public const EXTRAS    = ['Monitor', 'Keyboard & mouse', 'Windows licence', 'UPS'];

    /** Custom PC build guide for Nepal, with the AI builder embedded. */
    public function custom()
    {
        $faqs      = $this->faqs();
        $canonical = route('pcbuilder.custom');
        $title     = 'Custom PC Build in Nepal 2026: Budget Builds & AI Builder';
        $desc      = 'Plan a custom PC in Nepal for any budget. See what each price range buys, learn what every part does, and ask our AI to build your parts list.';
        $home      = rtrim(config('app.url'), '/') . '/';

        return Inertia::render('PCBuilder/Custom', [
            'tiers' => $this->tiers(),
            'steps' => $this->steps(),
            'parts' => $this->parts(),
            'faqs'  => $faqs,
            'seo'   => Seo::make([
                'title'       => $title,
                'description' => $desc,
                'canonical'   => $canonical,
                'keywords'    => 'custom pc build nepal, gaming pc nepal, pc build price in nepal, pc builder nepal, ai pc builder',
                'json_ld'     => [
                    '@context' => 'https://schema.org',
                    '@graph'   => [
                        [
                            '@type'       => 'WebPage',
                            '@id'         => $canonical . '#webpage',
                            'url'         => $canonical,
                            'name'        => $title,
                            'description' => $desc,
                            'isPartOf'    => ['@type' => 'WebSite', '@id' => $home . '#website', 'url' => $home, 'name' => config('app.name')],
                            'breadcrumb'  => ['@id' => $canonical . '#breadcrumb'],
                        ],
                        [
                            '@type'           => 'BreadcrumbList',
                            '@id'             => $canonical . '#breadcrumb',
                            'itemListElement' => [
                                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => $home],
                                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Custom PC Build', 'item' => $canonical],
                            ],
                        ],
                        [
                            '@type'      => 'FAQPage',
                            '@id'        => $canonical . '#faq',
                            'mainEntity' => array_map(fn ($f) => [
                                '@type'          => 'Question',
                                'name'           => $f['q'],
                                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f['a']],
                            ], $faqs),
                        ],
                    ],
                ],
            ]),
        ]);
    }

    /** What each budget realistically buys. `budget`/`purpose` pre-fill the AI builder. */
    private function tiers(): array
    {
        return [
            [
                'name'    => 'The study desk',
                'range'   => 'Under Rs 60,000',
                'short'   => 'Under 60K',
                'min'     => 0,
                'max'     => 60000,
                'budget'  => 55000,
                'purpose' => 'Office & Study',
                'for'     => 'Online classes, assignments, office work, YouTube and light games like Valorant on low settings.',
                'parts'   => [
                    'Processor' => 'Ryzen 5 5600G / 8500G or a Core i3 with built-in graphics',
                    'Graphics'  => 'Built into the processor, no separate card',
                    'Memory'    => '16GB, as two sticks',
                    'Storage'   => '512GB NVMe SSD',
                    'Power'     => '450–500W from a brand you recognise',
                ],
                'tip' => 'Skip the graphics card for now, but choose a power supply and case that can take one later. One upgrade turns this into a gaming PC.',
            ],
            [
                'name'    => 'Everyday + esports',
                'range'   => 'Rs 60,000 – 1 lakh',
                'short'   => '60K – 1L',
                'min'     => 60000,
                'max'     => 100000,
                'budget'  => 90000,
                'purpose' => 'Esports & Everyday',
                'for'     => 'Students who game on the side: esports at 1080p, Photoshop, coding and dozens of browser tabs.',
                'parts'   => [
                    'Processor' => '6-core Ryzen 5 5600 or Core i5-12400F',
                    'Graphics'  => 'Entry card such as RTX 3050 or Arc B570',
                    'Memory'    => '16GB dual channel',
                    'Storage'   => '512GB–1TB NVMe SSD',
                    'Power'     => '550W, 80 Plus Bronze',
                ],
                'tip' => 'The graphics card should take the biggest share of this budget. Pay for it before RGB fans or a glass case.',
            ],
            [
                'name'    => '1080p gaming',
                'range'   => 'Rs 1 – 1.5 lakh',
                'short'   => '1 – 1.5L',
                'min'     => 100000,
                'max'     => 150000,
                'budget'  => 130000,
                'purpose' => 'Gaming',
                'for'     => 'Most new AAA games at 1080p on high settings, with room to stream to friends on Discord.',
                'parts'   => [
                    'Processor' => 'Ryzen 5 7500F / 7600 or Core i5-14400F',
                    'Graphics'  => 'RTX 5060 or Radeon RX 9060 XT',
                    'Memory'    => '16GB DDR5 (32GB if it fits)',
                    'Storage'   => '1TB NVMe Gen4',
                    'Power'     => '650W, 80 Plus Bronze or better',
                ],
                'tip' => 'Go with AM5 if you can. Years from now you can put a much faster Ryzen in the same motherboard and keep everything else.',
            ],
            [
                'name'    => 'High refresh & 1440p',
                'range'   => 'Rs 1.5 – 2.5 lakh',
                'short'   => '1.5 – 2.5L',
                'min'     => 150000,
                'max'     => 250000,
                'budget'  => 200000,
                'purpose' => 'Gaming',
                'for'     => 'Competitive players on 144Hz+ screens, 1440p gaming, streaming and YouTube-level editing.',
                'parts'   => [
                    'Processor' => 'Ryzen 7 7700 / 9700X or Core Ultra 5 245K',
                    'Graphics'  => 'RTX 5060 Ti 16GB, RTX 5070 or RX 9070',
                    'Memory'    => '32GB DDR5',
                    'Storage'   => '1–2TB NVMe Gen4',
                    'Power'     => '750W, 80 Plus Gold',
                ],
                'tip' => 'Get a card with at least 12GB of video memory. New games already use up 8GB at 1440p, and you’ll keep this GPU for years.',
            ],
            [
                'name'    => 'Creator & enthusiast',
                'range'   => 'Rs 2.5 – 4 lakh',
                'short'   => '2.5 – 4L',
                'min'     => 250000,
                'max'     => 400000,
                'budget'  => 320000,
                'purpose' => 'Video Editing',
                'for'     => 'Fast 1440p gaming, 4K timelines in Premiere or DaVinci Resolve, Blender renders and streaming at the same time.',
                'parts'   => [
                    'Processor' => 'Ryzen 7 9800X3D for gaming, or a Ryzen 9 / Core Ultra 7 for heavy editing',
                    'Graphics'  => 'RTX 5070 Ti or RX 9070 XT',
                    'Memory'    => '32–64GB DDR5',
                    'Storage'   => '2TB NVMe Gen4, plus a second drive for footage',
                    'Power'     => '850W, 80 Plus Gold, ATX 3.1',
                ],
                'tip' => 'Decide whether this is mainly a gaming PC or a work PC. X3D chips are best for games, but more cores finish renders sooner.',
            ],
            [
                'name'    => '4K & workstation',
                'range'   => 'Rs 4 lakh and above',
                'short'   => '4L +',
                'min'     => 400000,
                'max'     => null,
                'budget'  => 500000,
                'purpose' => 'Workstation',
                'for'     => '4K gaming with ray tracing, 3D work, local AI models and big multi-camera edits, where waiting costs you money.',
                'parts'   => [
                    'Processor' => 'Ryzen 9 9950X3D / 9950X or Core Ultra 9 285K',
                    'Graphics'  => 'RTX 5080 or RTX 5090',
                    'Memory'    => '64GB DDR5 or more',
                    'Storage'   => '2TB Gen4/Gen5 for the system + 4TB for projects',
                    'Power'     => '1000W+, 80 Plus Gold or Platinum, native 12V-2x6 cable',
                ],
                'tip' => 'Set aside money for cooling and a roomy case, not only the GPU. A flagship card squeezed into a small case runs hot and loud and slows itself down.',
            ],
        ];
    }

    /** The guide tier a budget falls into, or null. */
    private function tierFor(int $budget): ?array
    {
        foreach ($this->tiers() as $tier) {
            if ($budget >= $tier['min'] && ($tier['max'] === null || $budget < $tier['max'])) {
                return $tier;
            }
        }

        return null;
    }

    private function steps(): array
    {
        return [
            ['title' => 'Start with what the PC is for', 'text' => 'List the three things you’ll do most, and note the monitor you’ll use. A 1080p 60Hz screen and a 1440p 165Hz screen need very different graphics cards.'],
            ['title' => 'Budget for everything', 'text' => 'Count the monitor, keyboard, mouse, Windows licence and a UPS too. A lot of first builds run out of money when it’s time to buy the case.'],
            ['title' => 'Choose the CPU and GPU together', 'text' => 'In a gaming build these two take roughly half the budget or more. Pair them so one doesn’t sit around waiting for the other.'],
            ['title' => 'Match the motherboard and memory', 'text' => 'Get a motherboard for that CPU’s socket and the RAM type it supports. Then pick an SSD big enough for your games and files.'],
            ['title' => 'Protect what you bought', 'text' => 'Get a good power supply, a case with real airflow, and a UPS for power cuts and voltage swings.'],
            ['title' => 'Check the warranty before you pay', 'text' => 'Ask if every part has local distributor warranty and get a VAT bill. Grey imports cost less, but warranty claims on them often go nowhere.'],
        ];
    }

    /** Short explainer for each component. `key` maps to an icon on the page. */
    private function parts(): array
    {
        return [
            [
                'key'     => 'cpu',
                'title'   => 'Processor (CPU)',
                'summary' => 'Runs Windows, your apps and game logic, and does the heavy work when you export a video or compile code.',
                'points'  => [
                    'Intel’s current desktop chips are Core Ultra 200S on the LGA 1851 socket. 12th–14th gen Core chips use the older LGA 1700 socket and often sell at good prices.',
                    'AMD’s AM5 platform (Ryzen 7000, 8000 and 9000) takes DDR5 only. AM4 with Ryzen 5000 is still the cheapest way in and uses DDR4.',
                    'Check the letters after the model number. On Intel, K means unlocked and F means no built-in graphics. On AMD, X3D means extra cache (great for games) and G means built-in graphics.',
                    'Six cores are plenty for gaming. Editing, 3D and streaming while you play are where extra cores help.',
                ],
            ],
            [
                'key'     => 'motherboard',
                'title'   => 'Motherboard',
                'summary' => 'Connects every part, and decides what you can add now and what you can upgrade later.',
                'points'  => [
                    'The socket must match your CPU. The chipset sets the features: B-series boards are right for most people, while Z (Intel) and X (AMD) are for overclocking and extra connections.',
                    'Size: ATX is full-size with the most slots. Micro-ATX is smaller and cheaper, and fine for a single graphics card. Mini-ITX is for tiny builds, with fewer slots and a higher price.',
                    'Count the M.2 slots and rear USB ports, and get built-in Wi-Fi if you can’t run a LAN cable from the router.',
                    'A board can sit in stock for months, so ask the shop to update the BIOS if you’re pairing it with a newer CPU.',
                ],
            ],
            [
                'key'     => 'gpu',
                'title'   => 'Graphics card (GPU)',
                'summary' => 'The biggest factor in gaming frame rates, and a big help in editing, 3D and AI work.',
                'points'  => [
                    'NVIDIA cards are the easiest to find in Nepal. Most come from partner brands like ASUS, MSI, Gigabyte, Zotac and Colorful. When AMD Radeon cards are in stock, they often give more frames for the money.',
                    'Video memory (VRAM) matters more each year. 8GB is fine for esports at 1080p, but aim for 12–16GB for 1440p and new AAA games.',
                    'Before buying, check the card’s length against the case and its power connector against the power supply.',
                    'For office and study use, built-in graphics are enough, so you can skip the card.',
                ],
            ],
            [
                'key'     => 'ram',
                'title'   => 'Memory (RAM)',
                'summary' => 'Short-term workspace for everything that’s open. Run out and the PC slows down.',
                'points'  => [
                    'In 2026, 16GB is the comfortable minimum for gaming. Get 32GB for editing, streaming or heavy multitasking, and 64GB or more for 3D, virtual machines and big projects.',
                    'Always buy two matching sticks. One stick runs in single channel and costs you frames, especially with built-in graphics.',
                    'The motherboard decides DDR4 vs DDR5. You can’t mix them, and new platforms take DDR5 only.',
                    'RAM prices have swung a lot since late 2025, so compare a few quotes before choosing how much to buy.',
                ],
            ],
            [
                'key'     => 'storage',
                'title'   => 'Storage',
                'summary' => 'Holds Windows, your games and your files. A fast SSD makes the whole PC feel snappy.',
                'points'  => [
                    'Put Windows and games on an NVMe SSD. Gen4 is the best value, and Gen5 mostly helps if you move huge files all day.',
                    '1TB fills up fast. Many new games take over 100GB each.',
                    'A hard drive is still the cheapest way to store photos, raw footage and backups.',
                ],
            ],
            [
                'key'     => 'psu',
                'title'   => 'Power supply (PSU)',
                'summary' => 'The part people save money on and then regret. It feeds every other part, so a bad one can damage them.',
                'points'  => [
                    'Rough sizes: 450–550W with no card or an entry card, 650W for mid-range, 750–850W for upper mid-range and 1000W+ for flagship GPUs.',
                    'Choose 80 Plus Gold for anything mid-range or above. High-end RTX 50 cards should get an ATX 3.0/3.1 unit with a native 12V-2x6 cable.',
                    'Buy a brand you know that offers a long warranty. In Nepal, where voltage often swings, that protects the rest of the PC.',
                    'Not sure? Use a PSU maker’s wattage calculator, then add some headroom.',
                ],
            ],
            [
                'key'     => 'cooling',
                'title'   => 'CPU cooling',
                'summary' => 'Keeps the processor at full speed without sounding like a ceiling fan.',
                'points'  => [
                    'For most Ryzen 5/7 and Core i5 chips, a good tower air cooler is quieter, cheaper and has no pump to fail.',
                    'Hot chips like Ryzen 9, Core Ultra 9 and unlocked i7/i9 need a 240–360mm liquid cooler (AIO) or a large dual-tower air cooler.',
                    'Dust builds up fast in Kathmandu, so clean the filters and fans every few months.',
                ],
            ],
            [
                'key'     => 'case',
                'title'   => 'Case (cabinet)',
                'summary' => 'More than looks. It decides how cool and quiet everything inside stays.',
                'points'  => [
                    'Pick a mesh front over solid glass or plastic. Parts need air.',
                    'Check graphics card length, cooler height and radiator support before you buy.',
                    'A mid-tower ATX case fits almost every build. Skip the cheapest cases that come with no fans.',
                ],
            ],
        ];
    }

    private function faqs(): array
    {
        return [
            ['q' => 'How much should I spend on a gaming PC in Nepal?', 'a' => 'For esports at 1080p, Rs 80,000 to 1 lakh is a solid start. For new AAA games on high settings at 1080p, plan for about Rs 1.2–1.5 lakh. Smooth 1440p gaming starts around Rs 2 lakh. These figures don’t include the monitor.'],
            ['q' => 'Intel or AMD — which should I choose?', 'a' => 'Both are good, and the shop price that week often decides it. AMD’s X3D chips are the fastest for gaming right now, and AM5 has a long upgrade path. Intel often has good deals on Core i5 chips and does well in productivity work. Compare the CPU and motherboard together, not the CPU alone.'],
            ['q' => 'DDR4 or DDR5?', 'a' => 'The platform decides. New AM5 and Intel Core Ultra boards take DDR5 only. DDR4 makes sense only if you’re saving money with an AM4 or older LGA 1700 build.'],
            ['q' => 'How much RAM do I need?', 'a' => '16GB for gaming and everyday use, 32GB for editing, streaming or lots of multitasking, and 64GB or more for 3D, virtual machines or large projects. Always use two matching sticks.'],
            ['q' => 'Do I need a separate graphics card?', 'a' => 'Not for study, office work, coding or watching videos. Built-in graphics handle those and even light games. You need a card for gaming at good settings, editing with effects, or 3D work.'],
            ['q' => 'What wattage power supply should I buy?', 'a' => 'Add up what the CPU and graphics card draw, then add around 40% headroom. Most 1080p gaming builds are comfortable on a good 650W unit. Upper mid-range builds need 750–850W, and flagship GPUs need 1000W or more.'],
            ['q' => 'Do I need a UPS for my PC?', 'a' => 'We strongly recommend one. Even a short power cut can corrupt a file you’re saving or interrupt a Windows update. A 1000–1500VA line-interactive UPS gives you time to save and shut down, and it smooths out voltage swings.'],
            ['q' => 'How accurate are the AI builder’s prices?', 'a' => 'Treat them as a starting estimate. The AI knows typical Nepal prices but not this week’s stock or the latest exchange rate. Use the list to plan, then confirm prices with a shop or ask us for a quote.'],
            ['q' => 'Custom PC, prebuilt or laptop?', 'a' => 'A custom desktop gives you the most performance for your money and is easy to upgrade and repair. A laptop is worth it only if you carry it around. Prebuilt PCs are convenient but often save money on the power supply and motherboard.'],
        ];
    }

    public function recommend(Request $request)
    {
        $request->validate([
            'budget'      => 'required|numeric|min:5000',
            'purpose'     => 'required|string|max:100',
            'cpu'         => 'nullable|string|max:100',
            'gpu'         => 'nullable|string|max:100',
            'ram'         => 'nullable|string|max:50',
            'storage'     => 'nullable|string|max:100',
            'motherboard' => 'nullable|string|max:100',
            'psu'         => 'nullable|string|max:50',
            'cooling'     => 'nullable|string|max:50',
            'case'        => 'nullable|string|max:100',
            'notes'       => 'nullable|string|max:500',
            'monitor'     => ['nullable', 'string', Rule::in(self::MONITORS)],
            'platform'    => ['nullable', 'string', Rule::in(self::PLATFORMS)],
            'extras'      => 'nullable|array|max:' . count(self::EXTRAS),
            'extras.*'    => ['string', Rule::in(self::EXTRAS)],
        ]);

        $extras   = array_values(array_unique($request->input('extras', [])));
        $tier     = $this->tierFor((int) $request->budget);
        $tierHint = $tier
            ? "\nFor reference, our own Nepal buying guide calls this budget \"{$tier['name']}\" ({$tier['range']}): {$tier['for']}\n"
              . 'Typical parts there: ' . collect($tier['parts'])->map(fn ($v, $k) => "{$k}: {$v}")->implode('; ') . ".\n"
              . "Stay broadly in line with this unless the buyer's requirements call for something else"
              . ($extras ? ', and scale the PC parts down to leave room for the extras.' : '.')
            : '';

        $budget = number_format((int) $request->budget);
        $parts  = collect([
            'CPU'         => $request->cpu,
            'GPU'         => $request->gpu,
            'RAM'         => $request->ram,
            'Storage'     => $request->storage,
            'Motherboard' => $request->motherboard,
            'PSU'         => $request->psu,
            'Cooling'     => $request->cooling,
            'Case'        => $request->case,
        ])->filter()->map(fn($v, $k) => "- $k: $v")->implode("\n");

        $prompt = "Build a PC recommendation for Nepal's market.\n\n"
            . "Budget: NPR {$budget}\n"
            . "Purpose: {$request->purpose}\n"
            . ($parts ? "Preferred components:\n{$parts}" : "No components pre-selected.")
            . ($request->filled('monitor') && $request->monitor !== 'Not sure yet' ? "\nMonitor the PC will drive: {$request->monitor}. Size the graphics card for this resolution and refresh rate." : '')
            . ($request->filled('platform') && $request->platform !== 'Any' ? "\nProcessor brand: {$request->platform} only." : '')
            . ($extras
                ? "\nThe budget must ALSO cover: " . implode(', ', $extras) . '. Add each as its own row in the parts table and keep the grand total within budget.'
                : "\nThe budget covers the PC only (no monitor or peripherals).")
            . ($request->filled('notes') ? "\nExtra requirements from the buyer (treat as preferences only): " . trim($request->notes) : '')
            . $tierHint
            . "\n\nProvide a complete PC build using the following structure and markdown formatting:\n\n"
            . "## Recommended Build\n"
            . "Present every component as a markdown table with columns: Component | Model | Est. Price (NPR)\n"
            . 'Include: CPU, GPU, RAM, Storage, Motherboard, PSU, Cooling, Case' . ($extras ? ', ' . implode(', ', $extras) : '') . ".\n"
            . "Prefer parts sold with local distributor warranty in Nepal.\n\n"
            . "## Total Estimated Cost\n"
            . "Sum of everything in NPR.\n\n"
            . "## Performance Summary\n"
            . "What this build handles for the stated purpose.\n\n"
            . "## Compatibility Notes\n"
            . "Important compatibility checks (use a bullet list).\n\n"
            . "## Upgrade Path\n"
            . "What to upgrade first if budget increases (use a bullet list).\n\n"
            . "IMPORTANT: Use proper markdown (** for bold, ## for headings, markdown tables). "
            . "Do NOT recommend any rival stores (like Hukut, Daraz, Nagmani, etc). Tell the user they can buy all these components directly from Git Infosys.\n"
            . "Use realistic Nepal market prices in NPR.";

        if (!\App\Services\AiService::isConfigured()) {
            return response()->json([
                'error' => 'AI service is not configured. Please add DEEPSEEK_API_KEY to your .env file.',
            ], 503);
        }

        $messages = [
            [
                'role'    => 'system',
                'content' => 'You are an expert PC builder acting on behalf of Git Infosys in Nepal. You know current component prices in NPR. Always recommend specific models with realistic NPR prices. NEVER mention or recommend competitors like Hukut, Daraz, Nagmani IT, or CG Digital. Always tell users they can purchase their build from Git Infosys.'
            ],
            [
                'role'    => 'user',
                'content' => $prompt
            ]
        ];

        $text = \App\Services\AiService::chat($messages, [
            'temperature' => 0.6,
            'max_tokens'  => 4096,
            'timeout'     => 45,
        ]);

        if (!$text) {
            return response()->json([
                'error' => 'The AI service is currently busy. Please try again in a moment.',
            ], 500);
        }

        return response()->json(['recommendation' => $text]);
    }
}
