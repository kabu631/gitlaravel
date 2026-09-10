<?php

namespace Database\Seeders;

use App\Models\NewsArticle;
use App\Models\User;
use Illuminate\Database\Seeder;

class TechNewsSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first() ?? User::create([
            'name'     => 'Editor Team',
            'email'    => 'editor@gitinfosys.com.np',
            'password' => bcrypt('password'),
            'role'     => 'admin',
        ]);

        $articles = [
            [
                'user_id'          => $admin->id,
                'title'            => 'The On-Device AI Revolution: How NPUs, Gemini Nano & Apple Intelligence Reshape Smartphones',
                'slug'             => 'on-device-ai-revolution-npu-gemini-nano-apple-intelligence',
                'category'         => 'ai',
                'is_published'     => true,
                'views_count'      => 4520,
                'meta_description' => 'A deep dive into on-device AI, neural NPUs, and running local LLMs without cloud latency in Nepal.',
                'content'          => '
<h2>The Shift from Cloud AI to Edge Silicon</h2>
<p>For the past two years, AI services like ChatGPT, Midjourney, and Claude were bound to massive cloud data centers. Every request traveled across undersea cables and international bandwidth, incurring latency, cellular data usage, and privacy concerns.</p>
<p>In 2024 and 2025, that reality is reversing dramatically. Modern silicon architectures — spearheaded by <strong>Qualcomm Snapdragon 8 Gen 3/4, Apple A18 Pro, and MediaTek Dimensity 9300+</strong> — feature dedicated Neural Processing Units (NPUs) capable of exceeding 45 to 80 TOPS (Trillion Operations Per Second).</p>

<h2>Why On-Device AI Matters for Nepali Users</h2>
<ul>
  <li><strong>Zero Data Consumption:</strong> Transcription, voice summarization, and photo editing execute locally without consuming mobile data bundles.</li>
  <li><strong>Instant Offline Latency:</strong> Real-time language translation works seamlessly even in remote trekking zones with zero cellular signal.</li>
  <li><strong>Complete Privacy:</strong> Your personal photos, health metrics, and confidential documents never leave the handset’s encrypted enclave.</li>
</ul>

<h2>The Leading Silicon Competitors</h2>
<h3>1. Qualcomm Snapdragon Hexagon NPU</h3>
<p>Found on flagship devices like the Samsung Galaxy S24 Ultra and OnePlus 12, Qualcomm’s NPU runs quantized 7-billion parameter LLMs at blazing-fast token generation speeds directly on device.</p>

<h3>2. Apple 16-Core Neural Engine</h3>
<p>Paired with unified memory architectures on iPhone 16 and M4 Macs, Apple Intelligence handles generative writing tools, clean-up tools in Photos, and Siri natural language understanding with zero cloud round-trips.</p>

<h2>Editorial Verdict: Is It Worth Upgrading?</h2>
<p>If you purchase a smartphone in Nepal expecting it to last 3 to 5 years, investing in an AI-capable SoC with at least 12GB of RAM is now essential. Operating systems will increasingly allocate 3GB to 4GB of unified memory exclusively to on-device neural weights.</p>
                ',
            ],
            [
                'user_id'          => $admin->id,
                'title'            => 'Nvidia RTX 50-Series Blackwell GPUs: What Gamers & AI Developers in Nepal Need to Know',
                'slug'             => 'nvidia-rtx-50-series-blackwell-gpus-nepal-guide',
                'category'         => 'gpu',
                'is_published'     => true,
                'views_count'      => 6340,
                'meta_description' => 'Preview of Nvidia RTX 5090, RTX 5080, GDDR7 speeds, and expected GPU pricing in Nepal.',
                'content'          => '
<h2>The Next Frontier in PC Graphics & AI Acceleration</h2>
<p>Nvidia is preparing its flagship <strong>Blackwell architecture</strong> for consumer desktop and laptop GPUs. Succeeding the legendary Ada Lovelace (RTX 40-series), the GeForce RTX 50-series promises not merely incremental rasterization uplifts, but a paradigm shift in neural rendering and local AI fine-tuning.</p>

<h2>Key Architecture Highlights</h2>
<ul>
  <li><strong>GDDR7 Memory:</strong> Bandwidth exceeding 28 to 32 Gbps, up to a staggering 1.7 TB/s memory throughput on the RTX 5090.</li>
  <li><strong>PCIe 5.0 Interface:</strong> Doubled interconnect speeds crucial for high-throughput AI datasets and DirectStorage gaming.</li>
  <li><strong>DLSS 4 Neural Frame Generation:</strong> Multi-frame generation driven by next-generation Tensor Cores with FP4 precision support.</li>
  <li><strong>TSMC 4NP Custom Node:</strong> Exceptional power efficiency curves across both enthusiast desktops and thin-and-light gaming laptops.</li>
</ul>

<h2>Expected Pricing and Availability in Nepal</h2>
<p>Due to 13% VAT, customs tariffs, and freight costs, graphics cards in Nepal typically carry a 15% to 25% premium above US MSRP. For creative professionals in Kathmandu working with Blender, DaVinci Resolve, Stable Diffusion, or PyTorch, the upcoming RTX 5070 and 5080 will offer the highest compute-per-rupee ratio.</p>

<h2>Where to Buy Genuine GPUs in Nepal</h2>
<p>Always verify authorized distributor warranty stickers when purchasing discrete graphics cards. Our retail partner <strong>Onin Infosys</strong> supplies authentic cards with official manufacturer warranties, VAT bills, and dedicated service support.</p>
                ',
            ],
            [
                'user_id'          => $admin->id,
                'title'            => 'Electronic Price Hikes in Nepal: Why RAM, SSDs, and Flagship Phones Are Getting More Expensive',
                'slug'             => 'electronic-price-hikes-nepal-ram-ssd-smartphones-analysis',
                'category'         => 'price-trends',
                'is_published'     => true,
                'views_count'      => 8120,
                'meta_description' => 'Market analysis on why computer hardware, smartphones, and consumer electronics are witnessing price increases in Nepal.',
                'content'          => '
<h2>The Supply Chain Perfect Storm</h2>
<p>Nepali tech enthusiasts and PC builders have noticed a steep upward trend in hardware pricing over the last two quarters. From DDR5 memory kits to PCIe 4.0 NVMe SSDs and flagship smartphones, costs have climbed between 15% and 35%. What is driving this surge?</p>

<h2>The 4 Primary Catalysts</h2>
<h3>1. Global AI Data Center Wafer Allocation</h3>
<p>Semiconductor giants like Samsung, SK Hynix, and Micron have reallocated significant fabrication capacity toward High-Bandwidth Memory (HBM3e) for enterprise AI clusters like Nvidia H100 and B200. This deliberate production reduction has choked consumer NAND flash and DRAM supply, driving wholesale spot prices up.</p>

<h3>2. Foreign Exchange Fluctuations (USD vs NPR)</h3>
<p>Consumer electronics in Nepal are strictly dollar-denominated import goods. Any depreciation of the Nepali Rupee against the US Dollar directly inflates landing costs at the Birgunj and Tatopani customs entry points.</p>

<h3>3. MDMS Registration & Mandatory 13% VAT Compliance</h3>
<p>The Nepal Telecommunications Authority (NTA) strictly enforces the Mobile Device Management System (MDMS). Grey-market imports without VAT bills are automatically blocked on Nepali cellular networks, ensuring all legal units carry official import duties and consumer protections.</p>

<h3>4. Rising Packaging and 3nm Wafer Costs</h3>
<p>TSMC’s latest 3-nanometer wafer fabrication costs exceed $20,000 per wafer — nearly triple the cost of mature 7nm nodes. This forces smartphone manufacturers to raise base MSRPs on flagship tiers.</p>

<h2>Smart Buying Advice</h2>
<p>If you plan to upgrade storage or RAM in your laptop or desktop, purchase verified inventory now before additional wafer contract adjustments take effect later in the fiscal quarter.</p>
                ',
            ],
            [
                'user_id'          => $admin->id,
                'title'            => 'From Cyberpunk to Reality: The Sci-Fi Cinema Tech That Actually Exists Today',
                'slug'             => 'from-cyberpunk-to-reality-sci-fi-cinema-tech-exists-today',
                'category'         => 'sci-fi',
                'is_published'     => true,
                'views_count'      => 5890,
                'meta_description' => 'Exploring how legendary sci-fi films predicted modern consumer tech like transparent MicroLEDs and neural interfaces.',
                'content'          => '
<h2>When Movie Fiction Becomes Consumer Reality</h2>
<p>For decades, science-fiction directors like Ridley Scott (<em>Blade Runner</em>), Steven Spielberg (<em>Minority Report</em>), and the creators of <em>Ghost in the Shell</em> imagined fantastical technologies that seemed centuries away. Yet in 2024, many of these dazzling inventions are sitting on laboratory workbenches and consumer store shelves.</p>

<h2>1. Transparent MicroLED Displays (<em>Minority Report</em>)</h2>
<p>Remember Tom Cruise manipulating transparent glass floating user interfaces? Samsung and LG recently unveiled fully functional transparent MicroLED panels at tech expos, boasting high transparency with zero bezel borders and pitch-black contrast ratios.</p>

<h2>2. Neural Prosthetics and Brain-Computer Interfaces (<em>Cyberpunk 2077</em>)</h2>
<p>Direct brain-to-digital input is no longer confined to tabletop RPGs. Clinical trials from companies like Neuralink and Synchron have allowed paralyzed patients to navigate macOS and Windows, play chess, and send text messages purely through neural signal decoding.</p>

<h2>3. Autonomous Bipedal Humanoids (<em>I, Robot</em>)</h2>
<p>Boston Dynamics Atlas and Tesla Optimus have transitioned from scripted mechanical demonstrations to autonomous neural network-driven locomotion, handling complex industrial tasks, sorting battery cells, and recognizing household objects using vision-language-action (VLA) AI models.</p>

<h2>4. Generative Holographic Video Calls (<em>Star Wars</em>)</h2>
<p>Google’s Project Starline uses 3D light-field cameras, spatial audio, and real-time volumetric streaming to create realistic holographic telepresence that makes remote communication feel like sitting across the table without wearing headsets.</p>

<h2>The Verdict</h2>
<p>We are living inside the science fiction that fascinated previous generations. The challenge of our decade is no longer technological feasibility, but human ethics, data privacy, and equitable access.</p>
                ',
            ],
        ];

        foreach ($articles as $art) {
            NewsArticle::updateOrCreate(['slug' => $art['slug']], $art);
        }
    }
}
