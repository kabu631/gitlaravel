<?php

namespace Database\Seeders;

use App\Models\PageContent;
use Illuminate\Database\Seeder;

class PageContentSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'page'             => 'about',
                'heading'          => "Nepal's Trusted Tech Platform",
                'subheading'       => "We help Nepali consumers make smarter, more confident tech purchasing decisions through honest reviews, real-time price tracking, and expert guides.",
                'meta_description' => "Learn about Git Infosys — the team, mission, and values behind Nepal's leading tech review, gadget comparison, and price tracking platform.",
                'extra'            => [
                    'mission' => "To be Nepal's most trusted source for gadget reviews, price comparisons, and tech news — empowering every buyer with unbiased, data-driven insights.",
                    'vision'  => "A Nepal where every consumer has access to transparent, up-to-date technology information and can shop with complete confidence.",
                    'story'   => "Git Infosys started as a passion project by a group of tech enthusiasts who were frustrated by the lack of reliable, localized tech information in Nepal. What began as a simple price tracker has grown into Nepal's comprehensive tech ecosystem — covering reviews, comparisons, buying guides, and an e-commerce hub. Today, we serve thousands of Nepali consumers every month, helping them find the right gadgets at the right prices.",
                ],
            ],
            [
                'page'             => 'contact',
                'heading'          => 'Get In Touch',
                'subheading'       => "Have a question, feedback, or partnership inquiry? We'd love to hear from you.",
                'meta_description' => "Have a question, review request, or partnership proposal? Contact the Git Infosys team and we'll get back to you shortly.",
                'extra'            => [
                    'address' => 'Pako, New Road, Kathmandu - Nepal',
                    'email'   => 'info@gitinfosys.com.np',
                    'phone'   => '+977-985-7039307',
                    'hours'   => 'Sun – Fri: 10 AM – 8 PM',
                    'map_lat' => '27.7172',
                    'map_lng' => '85.3240',
                ],
            ],
            [
                'page'             => 'services',
                'heading'          => 'Our Platform Services',
                'subheading'       => "Git Infosys is Nepal's ultimate tech ecosystem. We offer an integrated suite of tools, reviews, and shopping experiences to make your tech life smarter.",
                'meta_description' => "Explore what Git Infosys offers — gadget reviews, price comparison, buying guides, sponsored content, and more for Nepal's tech community.",
            ],
            [
                'page'             => 'terms',
                'heading'          => 'Terms & Conditions',
                'subheading'       => 'Please read these terms carefully before using our platform.',
                'meta_description' => "Read the terms and conditions governing your use of the Git Infosys website, products, and services.",
                'body'             => '<h2>1. Acceptance of Terms</h2><p>By accessing and using the Git Infosys website, you accept and agree to be bound by the terms and provisions of this agreement. If you do not agree to these terms, please do not use our platform.</p><h2>2. Use of the Platform</h2><p>Git Infosys provides technology product reviews, comparisons, news, and an e-commerce hub. Our platform is intended for informational purposes to help consumers make informed purchasing decisions.</p><ul><li>You must be at least 16 years old to use this platform.</li><li>You agree not to misuse the platform or help anyone else do so.</li><li>You may not use the platform for any illegal or unauthorized purpose.</li></ul><h2>3. Product Information &amp; Pricing</h2><p>We make every effort to display accurate product information and pricing. However, prices and availability are subject to change without notice. Git Infosys is not responsible for pricing errors or discrepancies between our listed prices and those at retail locations.</p><h2>4. User Accounts</h2><p>When you create an account with us, you must provide accurate and complete information. You are responsible for maintaining the security of your account and password. You agree to notify us immediately of any unauthorized use of your account.</p><h2>5. Intellectual Property</h2><p>All content on Git Infosys, including text, graphics, logos, images, and software, is the property of Git Infosys and is protected by copyright and intellectual property laws. You may not reproduce, distribute, or create derivative works without our express written permission.</p><h2>6. Purchases &amp; Payments</h2><p>All purchases made through our platform are subject to product availability. We reserve the right to refuse or cancel any order at any time. Refunds and returns are handled according to our return policy communicated at the time of purchase.</p><h2>7. Limitation of Liability</h2><p>Git Infosys shall not be liable for any indirect, incidental, special, consequential, or punitive damages resulting from your use of or inability to use the platform. Our total liability shall not exceed the amount you paid for the specific product or service.</p><h2>8. Changes to Terms</h2><p>We reserve the right to modify these terms at any time. Changes will be effective immediately upon posting on the website. Your continued use of the platform constitutes acceptance of the modified terms.</p><h2>9. Contact</h2><p>If you have any questions about these Terms and Conditions, please contact us via our <a href="/contact">contact page</a>.</p>',
            ],
            [
                'page'             => 'privacy',
                'heading'          => 'Privacy Policy',
                'subheading'       => 'Your privacy is important to us. This policy explains how we collect and use your data.',
                'meta_description' => "Understand how Git Infosys collects, uses, and protects your personal information when you use our platform.",
                'body'             => '<h2>1. Information We Collect</h2><p>We collect information you provide directly to us, such as when you create an account, make a purchase, or contact us. This may include:</p><ul><li>Name, email address, and password</li><li>Billing and shipping address</li><li>Phone number (for order communications)</li><li>Purchase history and preferences</li><li>Comments and reviews you submit</li></ul><h2>2. How We Use Your Information</h2><p>We use the information we collect to:</p><ul><li>Process transactions and send related information</li><li>Send promotional communications (with your consent)</li><li>Respond to comments and questions</li><li>Improve our products and services</li><li>Monitor and analyze usage patterns</li></ul><h2>3. Information Sharing</h2><p>We do not sell, trade, or otherwise transfer your personal information to outside parties except to trusted third parties who assist us in operating our website (such as payment processors and shipping partners), as long as those parties agree to keep this information confidential.</p><h2>4. Cookies</h2><p>Our site uses cookies to enhance your browsing experience. Cookies are small files that a site transfers to your computer\'s hard drive through your Web browser that enables the site to recognize your browser and remember certain information. You can choose to disable cookies through your browser settings.</p><h2>5. Data Security</h2><p>We implement a variety of security measures to maintain the safety of your personal information. Your personal information is contained behind secured networks and is only accessible by a limited number of persons who have special access rights to such systems.</p><h2>6. Your Rights</h2><p>You have the right to:</p><ul><li>Access the personal information we hold about you</li><li>Request correction of inaccurate data</li><li>Request deletion of your account and associated data</li><li>Opt-out of marketing communications at any time</li></ul><h2>7. Third-Party Links</h2><p>Our website may contain links to other sites. We are not responsible for the privacy practices or the content of such websites. We encourage you to review the privacy policies of any third-party sites you visit.</p><h2>8. Changes to This Policy</h2><p>We may update this privacy policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page. You are advised to review this Privacy Policy periodically for any changes.</p><h2>9. Contact Us</h2><p>If you have any questions about this Privacy Policy, please <a href="/contact">contact us</a>.</p>',
            ],
        ];

        foreach ($pages as $data) {
            PageContent::updateOrCreate(['page' => $data['page']], $data);
        }
    }
}
