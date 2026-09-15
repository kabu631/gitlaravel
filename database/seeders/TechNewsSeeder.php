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
            [
                'user_id'          => $admin->id,
                'title'            => 'iPhone 17 Pro Max Leaks: 2nm A19 Pro, Slimmer Bezels & Nepal Pricing Radar',
                'slug'             => 'iphone-17-pro-max-leaks-2nm-a19-pro-nepal-pricing-radar',
                'category'         => 'rumors',
                'is_published'     => true,
                'views_count'      => 9450,
                'meta_description' => 'Supply-chain leaks reveal Apple\'s 2nm A19 Pro node, enhanced CenterStage front camera, and expected pricing in Kathmandu.',
                'content'          => '
<h2>Apple\'s Next Leap: What Early Leaks Reveal</h2>
<p>While the iPhone 16 generation cements Apple Intelligence across global markets, supply-chain analysts from Foxconn and TSMC have already begun disclosing architectural details for the <strong>iPhone 17 Pro Max</strong>.</p>

<h2>Key Rumored Specifications</h2>
<ul>
  <li><strong>TSMC 2nm Fabrication (N2):</strong> Expected to be the first commercial handset powered by a sub-3nm node, yielding a 15% clock speed boost and 30% power reduction.</li>
  <li><strong>24MP CenterStage Front Camera:</strong> Upgraded from the current 12MP sensor, bringing 6-element plastic lens optics for ultra-crisp FaceTime and video logs.</li>
  <li><strong>Narrowed Dynamic Island:</strong> Enhanced meta-lens proximity sensors will shrink the screen cutout area by approximately 35%.</li>
  <li><strong>Anti-Reflective Armor Glass:</strong> New scratch-resistant coating superior to Ceramic Shield, engineered to minimize glare under Nepal\'s intense high-altitude sunlight.</li>
</ul>

<h2>Projected Nepal Pricing & Launch Timeline</h2>
<p>Historically, authorized Apple importers in Nepal (Generation Next Communications) launch new flagship iPhones within 3 to 4 weeks of the global keynote. Expected Nepal retail pricing for the iPhone 17 Pro Max 256GB base model is estimated at <strong>NPR 2,19,999</strong> inclusive of 13% VAT and official MDMS compliance.</p>
                ',
            ],
            [
                'user_id'          => $admin->id,
                'title'            => 'Samsung Galaxy S25 Ultra Leak Roundup: Snapdragon 8 Elite, Rounder Titanium Frame & Nepal Launch Window',
                'slug'             => 'samsung-galaxy-s25-ultra-leak-roundup-snapdragon-8-elite-nepal',
                'category'         => 'rumors',
                'is_published'     => true,
                'views_count'      => 8720,
                'meta_description' => 'Full breakdown of leaked specs, Galaxy AI 2.0 features, and expected Nepal availability for Samsung\'s 2025 flagship.',
                'content'          => '
<h2>Ergonomic Refinements and Raw Computing Might</h2>
<p>Samsung\'s Galaxy S25 Ultra is poised to address the single most frequent ergonomic critique of the S24 Ultra: sharp palm-digging corners. CAD renders and dummy units confirm rounder edges combined with Grade 5 titanium rails.</p>

<h2>Confirmed & Highly Anticipated Hardware</h2>
<ul>
  <li><strong>Qualcomm Snapdragon 8 Elite for Galaxy:</strong> Custom Oryon cores clocked past 4.3 GHz, delivering unrivaled single-core numbers in Geekbench 6.</li>
  <li><strong>16GB LPDDR5X RAM Standard:</strong> Upgraded from 12GB to ensure seamless execution of localized Galaxy AI 2.0 models without cloud dependency.</li>
  <li><strong>50MP 5x & 3x Telephoto Sensors:</strong> Upgraded ISOCELL sensor array providing uniform 8K video switching across all optical focal lengths.</li>
  <li><strong>Satellite SOS Connectivity:</strong> Nepal-ready emergency satellite messaging protocols in collaboration with regional satcom operators.</li>
</ul>

<h2>Kathmandu Launch & Pre-Order Expectations</h2>
<p>Samsung Nepal typically opens pre-bookings at official experience stores across Durbarmarg, New Road, and Pokhara within 48 hours of Galaxy Unpacked. Early pre-order perks usually include free Galaxy Buds or steep discounts on Galaxy Watches.</p>
                ',
            ],
            [
                'user_id'          => $admin->id,
                'title'            => 'Asus ROG Zephyrus G16 (2025) Leaked Benchmarks: RTX 5080 Laptop GPU Performance Revealed',
                'slug'             => 'asus-rog-zephyrus-g16-2025-rtx-5080-benchmarks-leaked',
                'category'         => 'rumors',
                'is_published'     => true,
                'views_count'      => 7630,
                'meta_description' => 'Early engineering samples show massive gen-over-gen graphics gains. Here is what Nepali creators and gamers should expect.',
                'content'          => '
<h2>The Thin-and-Light Gaming Crown Defended</h2>
<p>Asus shocked the PC world with its CNC-milled aluminum unibody Zephyrus G16. Early engineering leaks from CES test benches indicate that the 2025 refresh will pack Nvidia\'s mobile Blackwell architecture without increasing chassis thickness.</p>

<h2>Leaked Specifications</h2>
<ul>
  <li><strong>GeForce RTX 5080 Laptop GPU:</strong> Featuring 16GB GDDR7 VRAM on a 256-bit bus, running at up to 150W TGP with dynamic boost.</li>
  <li><strong>Intel Core Ultra 9 285H (Arrow Lake):</strong> Hybrid architecture with 16 cores and integrated NPU exceeding 50 TOPS.</li>
  <li><strong>2.5K 240Hz ROG Nebula OLED:</strong> 0.2ms response time, 100% DCI-P3 color gamut, and VESA DisplayHDR True Black 500 certification.</li>
</ul>

<h2>Retail Availability in Nepal</h2>
<p>For video editors, 3D rendering artists, and AAA gamers in Kathmandu, the 2025 Zephyrus G16 will represent the pinnacle of portable workstation performance. Estimated starting price in Nepal will be around <strong>NPR 3,25,000</strong> with official Asus 2-year international warranty.</p>
                ',
            ],
            [
                'user_id'          => $admin->id,
                'title'            => 'Qualcomm Oryon CPU & Snapdragon X Elite Laptops: Nepal Availability & Battery Benchmarks',
                'slug'             => 'qualcomm-oryon-snapdragon-x-elite-laptops-nepal-battery-benchmarks',
                'category'         => 'technology',
                'is_published'     => true,
                'views_count'      => 6210,
                'meta_description' => 'ARM-based Windows laptops are transforming battery endurance. How the Snapdragon X Elite performs in Nepal\'s tech market.',
                'content'          => '
<h2>The Windows on ARM Revolution Finally Arrives</h2>
<p>For years, Windows laptops suffered from battery life compromises when compared to Apple Silicon MacBooks. The Snapdragon X Elite, featuring Qualcomm\'s custom Oryon architecture, has fundamentally equalized the playing field.</p>

<h2>Real-World Testing Highlights</h2>
<ul>
  <li><strong>20+ Hour Video Playback:</strong> In Kathmandu office conditions, laptops like the Surface Pro 11 and Asus Vivobook S 15 easily sail through two complete work days without a wall charger.</li>
  <li><strong>Whisper-Quiet Thermals:</strong> Zero thermal throttling during heavy web development and Figma prototyping workflows.</li>
  <li><strong>Prism Emulation Efficiency:</strong> Legacy x86/x64 Windows applications run smoothly through Microsoft\'s updated translation layer.</li>
</ul>

<h2>Where to Buy in Nepal</h2>
<p>Authorized retail channels in Putalisad and New Road now carry certified Copilot+ PCs with full local warranty and Microsoft 365 licensing packages.</p>
                ',
            ],
            [
                'user_id'          => $admin->id,
                'title'            => 'Wi-Fi 7 Adoption in Nepal: Compatible Routers, Speeds & ISP Support in 2024',
                'slug'             => 'wifi-7-adoption-nepal-routers-speed-isp-support',
                'category'         => 'technology',
                'is_published'     => true,
                'views_count'      => 5480,
                'meta_description' => 'Nepal Telecommunications Authority approves new spectrum. Here is what Nepali home and office users need to know about Wi-Fi 7.',
                'content'          => '
<h2>Next-Gen Wireless Connectivity in Kathmandu Valley</h2>
<p>As Nepali fiber ISPs like WorldLink, Vianet, and Classic Tech deploy multi-gigabit fiber packages, local Wi-Fi congestion in dense urban corridors has become the new bottleneck. Enter <strong>Wi-Fi 7 (802.11be)</strong>.</p>

<h2>The 3 Key Upgrades</h2>
<ul>
  <li><strong>320 MHz Channel Width:</strong> Doubled channel size allows massive file transfers between local NAS devices and workstations.</li>
  <li><strong>Multi-Link Operation (MLO):</strong> Handsets can simultaneously connect across 5GHz and 6GHz bands, cutting latency spikes to near-zero for competitive mobile gaming.</li>
  <li><strong>4096-QAM Modulation:</strong> 20% higher data transmission density compared to Wi-Fi 6E.</li>
</ul>

<h2>Equipment Recommendations</h2>
<p>TP-Link Archer BE800 and Asus RT-BE96U have received preliminary type approval in Nepal, making them prime candidates for tech studios and esports setups seeking future-proof infrastructure.</p>
                ',
            ],
        ];

        foreach ($articles as $art) {
            NewsArticle::updateOrCreate(['slug' => $art['slug']], $art);
        }
    }
}
