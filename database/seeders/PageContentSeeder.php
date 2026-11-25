<?php

namespace Database\Seeders;

use App\Models\PageContent;
use Illuminate\Database\Seeder;

class PageContentSeeder extends Seeder
{
    public function run(): void
    {
        foreach (self::pages() as $data) {
            PageContent::updateOrCreate(['page' => $data['page']], $data);
        }
    }

    /** Plain, checkable wording only: no rankings, visitor numbers or testing claims we can't back up. */
    public static function pages(): array
    {
        return [
            [
                'page'             => 'about',
                'heading'          => 'Gadget prices, specs and buying advice for Nepal',
                'subheading'       => 'Git Infosys helps people in Nepal compare devices, check what they really cost here, and decide what to buy.',
                'meta_description' => 'Who runs Git Infosys, how we collect prices in Nepal, and how our reviews and guides are written.',
                'extra'            => [
                    'mission' => 'To make buying a phone, laptop or gadget in Nepal simpler, with clear specifications, local prices and advice in plain language.',
                    'vision'  => 'Anyone in Nepal should be able to find out what a device costs, how it compares and whether it suits them before they walk into a shop.',
                    'story'   => 'Git Infosys started as a passion project by a group of tech enthusiasts who were frustrated by the lack of reliable, localized tech information in Nepal.',
                    'stats'   => [],
                    'method'  => [
                        ['title' => 'Prices', 'text' => 'We record prices from shops, distributors and brand announcements in Nepal, with the date we saw them. Prices change often, so check with the shop before you buy.'],
                        ['title' => 'Reviews', 'text' => 'Our reviews are based on specifications, Nepal prices and published test results. When we have not tested a device ourselves, the review says so.'],
                        ['title' => 'Partners', 'text' => 'Some price buttons lead to the shop of a business partner, and some product photos and prices come from it. The product page says so beside the button.'],
                        ['title' => 'Corrections', 'text' => 'If a price or specification is wrong, tell us through the contact page and we will check it.'],
                    ],
                ],
            ],
            [
                'page'             => 'contact',
                'heading'          => 'Get in touch',
                'subheading'       => 'Questions, corrections or partnership ideas: we would like to hear from you.',
                'meta_description' => 'Contact Git Infosys in New Road, Kathmandu, by phone, email or the form on this page.',
                'extra'            => [
                    'address' => 'Pako, New Road, Kathmandu - Nepal',
                    'email'   => 'info@gitinfosys.com.np',
                    'phone'   => '+977-985-7039307',
                    'hours'   => 'Sun – Fri: 10 AM – 8 PM',
                    'map_lat' => '27.7027051',
                    'map_lng' => '85.3099064',
                ],
            ],
            [
                'page'             => 'services',
                'heading'          => 'What you can do here',
                'subheading'       => 'Free tools and guides for choosing phones, laptops and other gadgets in Nepal.',
                'meta_description' => 'Compare devices, track prices in Nepal, read reviews and buying guides, and use calculators for EMI, customs duty and resale value.',
                'extra'            => [],
            ],
            [
                'page'             => 'terms',
                'heading'          => 'Terms & Conditions',
                'subheading'       => 'Please read these terms before using this website.',
                'meta_description' => 'The terms for using the Git Infosys website.',
                'body'             => <<<'HTML'
<h2>1. Acceptance of these terms</h2><p>By using the Git Infosys website you agree to these terms. If you do not agree with them, please do not use the website.</p>
<h2>2. What this website is for</h2><p>Git Infosys publishes device specifications, prices, comparisons, reviews, guides and news to help you decide what to buy. It is for general information.</p><ul><li>You must be at least 16 years old to create an account.</li><li>Do not misuse the website, try to break its security or copy it automatically in bulk.</li><li>Do not use the website for anything illegal.</li></ul>
<h2>3. Prices and product information</h2><p>We try to keep specifications and prices accurate, and we show when a price was recorded. Prices and stock change without notice and can differ between shops, so confirm them with the seller before you buy. Prices marked as expected or estimated are our own estimates. We are not responsible for differences between a price shown here and the price in a shop.</p>
<h2>4. Buying from shops</h2><p>Links such as “Check price at retailer” take you to another website. Your purchase, payment, delivery, warranty and returns are between you and that seller, under their terms.</p>
<h2>5. Calculators and AI tools</h2><p>EMI, customs duty, resale value and similar tools give estimates only. Answers from the AI assistant, PC builder and comparison verdict are generated automatically and can be wrong. Check anything important with the bank, the customs office, the shop or the manufacturer.</p>
<h2>6. Your account</h2><p>Give accurate details when you create an account and keep your password private. Tell us if you think someone else has used your account.</p>
<h2>7. Reviews and comments you post</h2><p>Post only your own honest opinion. We may remove content that is abusive, misleading, spam or illegal.</p>
<h2>8. Copyright</h2><p>Our text, design and software belong to Git Infosys. Product photos, names and logos belong to their owners and are used to identify the products. If you own an image and want it credited differently or removed, contact us.</p>
<h2>9. Limit of liability</h2><p>As far as the law allows, we are not liable for losses that come from using, or not being able to use, this website or from relying on its information.</p>
<h2>10. Changes</h2><p>We may update these terms. The version on this page is the one that applies.</p>
<h2>11. Contact</h2><p>Questions about these terms? Write to us through the contact page.</p>
HTML,
            ],
            [
                'page'             => 'privacy',
                'heading'          => 'Privacy Policy',
                'subheading'       => 'What we collect, why, and the choices you have.',
                'meta_description' => 'How Git Infosys collects, uses and protects personal information.',
                'body'             => <<<'HTML'
<h2>1. What we collect</h2><ul><li><strong>Account:</strong> your name, email address and an encrypted (hashed) password, if you create an account.</li><li><strong>Contact form:</strong> the name, email address, phone number and message you send us.</li><li><strong>What you post or save:</strong> reviews, comments, wishlist and saved comparisons.</li><li><strong>Technical data:</strong> your IP address, browser type and session, used to keep you signed in and to stop abuse.</li><li><strong>AI tools:</strong> the questions you type into the assistant, PC builder and comparison tool.</li></ul>
<h2>2. How we use it</h2><ul><li>To run your account and the features you use.</li><li>To answer your messages.</li><li>To keep the website secure, for example by limiting repeated sign-in attempts.</li><li>To improve the website.</li></ul><p>We do not sell your personal information.</p>
<h2>3. Who else sees it</h2><p>Companies that help us run the website, such as our hosting and email providers, process data for us. Questions you type into the AI tools are sent to an outside AI provider to produce the answer, so please do not include personal or confidential details in them. Maps on the contact page are loaded from OpenStreetMap. We may share information if the law requires it.</p>
<h2>4. Cookies and browser storage</h2><p>We use cookies to keep you signed in, protect forms and remember your cookie choice. Your theme and comparison list are stored in your own browser. You can clear or block them in your browser settings, but signing in will not work without cookies.</p>
<h2>5. How long we keep it</h2><p>We keep account information while your account exists. You can delete your account from your profile page. We keep contact messages for as long as we need them to help you.</p>
<h2>6. Security</h2><p>Passwords are stored hashed, sign-in attempts are limited and only authorised staff can open the admin area. No website can promise complete security, so use a password you do not use anywhere else.</p>
<h2>7. Your choices</h2><p>You can ask to see, correct or delete the personal information we hold about you. Use the contact page to ask.</p>
<h2>8. Links to other websites</h2><p>We link to shops, brands and news sources. Their own privacy policies apply there.</p>
<h2>9. Changes</h2><p>We may update this policy. The version on this page is the current one.</p>
<h2>10. Contact</h2><p>Questions about privacy? Write to us through the contact page.</p>
HTML,
            ],
        ];
    }

    /** Cards on the Services page. */
    public static function services(): array
    {
        return [
            ['In-depth reviews', 'What each device does well, where it falls short and whether it is worth its price in Nepal. If we have not tested it ourselves, the review says so.'],
            ['Side-by-side comparison', 'Put up to four devices next to each other and see every difference in price and specification.'],
            ['Price tracking', 'Recorded prices in Nepal with dates, so you can see what got cheaper and what went up.'],
            ['Tech news', 'Launches, leaks and price changes, with a focus on what they mean for buyers in Nepal.'],
            ['Buying tools', 'Estimate EMI instalments, customs duty and resale value, and plan a PC build for your budget.'],
            ['Buying guides', 'Which model to choose at each budget, with the jargon explained.'],
        ];
    }
}
