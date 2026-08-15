<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Coupon;
use App\Models\HostingPackage;
use App\Models\KnowledgeBase;
use App\Models\Server;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin users ───────────────────────────────────────────
        User::create([
            'name'           => 'Super Admin',
            'email'          => 'admin@skynetug.com',
            'password'       => Hash::make('Admin@1234'),
            'role'           => 'super_admin',
            'phone'          => '+256700000001',
            'country'        => 'UG',
            'city'           => 'Kampala',
            'is_active'      => true,
            'referral_code'  => Str::upper(Str::random(8)),
            'email_verified_at' => now(),
        ]);

        User::create([
            'name'           => 'Billing Manager',
            'email'          => 'billing@skynetug.com',
            'password'       => Hash::make('Billing@1234'),
            'role'           => 'billing_manager',
            'phone'          => '+256700000002',
            'country'        => 'UG',
            'is_active'      => true,
            'referral_code'  => Str::upper(Str::random(8)),
            'email_verified_at' => now(),
        ]);

        User::create([
            'name'           => 'Support Agent',
            'email'          => 'support@skynetug.com',
            'password'       => Hash::make('Support@1234'),
            'role'           => 'support_agent',
            'phone'          => '+256700000003',
            'country'        => 'UG',
            'is_active'      => true,
            'referral_code'  => Str::upper(Str::random(8)),
            'email_verified_at' => now(),
        ]);

        // ── Sample customers ──────────────────────────────────────
        $customers = [
            ['name' => 'John Kiggundu',  'email' => 'john@example.com',   'phone' => '+256772000001', 'city' => 'Kampala'],
            ['name' => 'Sarah Atuhaire', 'email' => 'sarah@example.com',  'phone' => '+256772000002', 'city' => 'Entebbe'],
            ['name' => 'Moses Tumwine',  'email' => 'moses@example.com',  'phone' => '+256772000003', 'city' => 'Jinja'],
        ];

        foreach ($customers as $c) {
            User::create([
                'name'              => $c['name'],
                'email'             => $c['email'],
                'password'          => Hash::make('Customer@1234'),
                'role'              => 'customer',
                'phone'             => $c['phone'],
                'country'           => 'UG',
                'city'              => $c['city'],
                'is_active'         => true,
                'referral_code'     => Str::upper(Str::random(8)),
                'email_verified_at' => now(),
            ]);
        }

        // ── Server ────────────────────────────────────────────────
        Server::create([
            'name'         => 'SkyNetug Main Server',
            'hostname'     => 'server1.skynetug.com',
            'ip_address'   => '197.157.0.1',
            'type'         => 'shared',
            'username'     => 'root',
            'max_accounts' => 500,
            'ns1'          => 'ns1.skynetug.com',
            'ns2'          => 'ns2.skynetug.com',
            'is_active'    => true,
        ]);

        // ── Hosting packages ──────────────────────────────────────
        $packages = [
            // Shared Hosting
            ['name'=>'Starter Hosting',       'slug'=>'starter',       'type'=>'shared',    'price_monthly'=>18000,  'price_yearly'=>180000,  'disk_space_mb'=>5120,   'email_accounts'=>5,   'databases'=>1,  'softaculous_included'=>true,  'ssl_included'=>true,  'is_featured'=>false, 'description'=>'Perfect for personal websites and blogs'],
            ['name'=>'Business Hosting',       'slug'=>'business',      'type'=>'shared',    'price_monthly'=>35000,  'price_yearly'=>360000,  'disk_space_mb'=>20480,  'email_accounts'=>20,  'databases'=>5,  'softaculous_included'=>true,  'ssl_included'=>true,  'is_featured'=>true,  'description'=>'Ideal for growing businesses'],
            ['name'=>'Professional Hosting',   'slug'=>'professional',  'type'=>'shared',    'price_monthly'=>65000,  'price_yearly'=>660000,  'disk_space_mb'=>51200,  'email_accounts'=>50,  'databases'=>20, 'softaculous_included'=>true,  'ssl_included'=>true,  'is_featured'=>false, 'description'=>'For high-traffic professional sites'],
            ['name'=>'Unlimited Hosting',      'slug'=>'unlimited',     'type'=>'shared',    'price_monthly'=>90000,  'price_yearly'=>960000,  'disk_space_mb'=>0,      'email_accounts'=>0,   'databases'=>0,  'softaculous_included'=>true,  'ssl_included'=>true,  'backup_included'=>true, 'is_featured'=>false, 'description'=>'Unlimited resources for power users'],
            // WordPress
            ['name'=>'WordPress Starter',      'slug'=>'wp-starter',    'type'=>'wordpress', 'price_monthly'=>20000,  'price_yearly'=>200000,  'disk_space_mb'=>10240,  'email_accounts'=>10,  'databases'=>2,  'softaculous_included'=>true,  'ssl_included'=>true,  'is_featured'=>false, 'description'=>'Fast WordPress hosting for bloggers'],
            ['name'=>'WordPress Business',     'slug'=>'wp-business',   'type'=>'wordpress', 'price_monthly'=>40000,  'price_yearly'=>400000,  'disk_space_mb'=>30720,  'email_accounts'=>30,  'databases'=>10, 'softaculous_included'=>true,  'ssl_included'=>true,  'backup_included'=>true, 'is_featured'=>true, 'description'=>'Optimised for business WordPress sites'],
            ['name'=>'Managed WordPress',      'slug'=>'wp-managed',    'type'=>'wordpress', 'price_monthly'=>80000,  'price_yearly'=>840000,  'disk_space_mb'=>0,      'email_accounts'=>0,   'databases'=>0,  'softaculous_included'=>true,  'ssl_included'=>true,  'backup_included'=>true, 'is_featured'=>false, 'description'=>'Fully managed WordPress environment'],
            // VPS
            ['name'=>'VPS Starter',            'slug'=>'vps-starter',   'type'=>'vps',       'price_monthly'=>120000, 'price_yearly'=>1200000, 'disk_space_mb'=>25600,  'email_accounts'=>0,   'databases'=>0,  'features'=>['1 vCPU','1 GB RAM','25 GB SSD','1 TB Bandwidth','Full Root Access'], 'is_featured'=>false, 'description'=>'Entry VPS for developers'],
            ['name'=>'VPS Business',           'slug'=>'vps-business',  'type'=>'vps',       'price_monthly'=>220000, 'price_yearly'=>2200000, 'disk_space_mb'=>51200,  'email_accounts'=>0,   'databases'=>0,  'features'=>['2 vCPU','2 GB RAM','50 GB SSD','2 TB Bandwidth','Full Root Access'], 'is_featured'=>true, 'description'=>'Powerful VPS for growing apps'],
            // Email Hosting
            ['name'=>'Email Starter',          'slug'=>'email-starter', 'type'=>'email',     'price_monthly'=>10000,  'price_yearly'=>100000,  'disk_space_mb'=>5120,   'email_accounts'=>5,   'databases'=>0,  'is_featured'=>false, 'description'=>'5 professional email accounts'],
            ['name'=>'Email Business',         'slug'=>'email-business','type'=>'email',     'price_monthly'=>20000,  'price_yearly'=>200000,  'disk_space_mb'=>20480,  'email_accounts'=>20,  'databases'=>0,  'is_featured'=>true, 'description'=>'20 email accounts with 20 GB storage'],
            // SSL
            ['name'=>'DV SSL Certificate',     'slug'=>'ssl-dv',        'type'=>'ssl',       'price_monthly'=>0,      'price_yearly'=>150000,  'disk_space_mb'=>0,      'email_accounts'=>0,   'databases'=>0,  'is_featured'=>false, 'description'=>'Domain Validation SSL for websites'],
            ['name'=>'Wildcard SSL',           'slug'=>'ssl-wildcard',  'type'=>'ssl',       'price_monthly'=>0,      'price_yearly'=>500000,  'disk_space_mb'=>0,      'email_accounts'=>0,   'databases'=>0,  'is_featured'=>false, 'description'=>'Covers domain and all subdomains'],
        ];

        foreach ($packages as $i => $pkg) {
            HostingPackage::create(array_merge([
                'price_biennially'    => ($pkg['price_yearly'] ?? 0) * 1.8,
                'bandwidth_mb'        => 0,
                'subdomains'          => 0,
                'addon_domains'       => 0,
                'parked_domains'      => 0,
                'ssl_included'        => $pkg['ssl_included'] ?? false,
                'softaculous_included'=> $pkg['softaculous_included'] ?? false,
                'backup_included'     => $pkg['backup_included'] ?? false,
                'features'            => $pkg['features'] ?? [],
                'is_featured'         => $pkg['is_featured'],
                'is_active'           => true,
                'sort_order'          => $i,
            ], $pkg));
        }

        // ── Coupons ───────────────────────────────────────────────
        Coupon::create([
            'code'        => 'WELCOME20',
            'name'        => '20% off for new customers',
            'type'        => 'percentage',
            'value'       => 20,
            'usage_limit' => 100,
            'expires_at'  => now()->addMonths(3)->toDateString(),
            'is_active'   => true,
        ]);

        Coupon::create([
            'code'        => 'SAVE50K',
            'name'        => '$ 50,000 off yearly plans',
            'type'        => 'fixed',
            'value'       => 50000,
            'usage_limit' => 50,
            'expires_at'  => now()->addMonths(2)->toDateString(),
            'is_active'   => true,
        ]);

        // ── Announcements ─────────────────────────────────────────
        Announcement::create([
            'title'        => 'Welcome to SkyNetug!',
            'content'      => "We're excited to welcome you to SkyNetug, Uganda's fastest growing web hosting provider. Enjoy fast, reliable hosting with 24/7 local support. Thank you for choosing us!",
            'status'       => 'published',
            'created_by'   => 1,
            'published_at' => now(),
        ]);

        Announcement::create([
            'title'        => 'New Payment: MTN Mobile Money Now Available',
            'content'      => 'You can now pay for all hosting and domain services using MTN Mobile Money. Simply select "MTN Mobile Money" at checkout and complete payment on your phone.',
            'status'       => 'published',
            'created_by'   => 1,
            'published_at' => now()->subDays(5),
        ]);

        // ── Knowledge Base ────────────────────────────────────────
        $articles = [
            ['title'=>'How to Get Started with SkyNetug',             'category'=>'Getting Started',    'content'=>"Welcome to SkyNetug!\n\n1. Register an account at skynetug.com\n2. Choose a hosting plan that fits your needs\n3. Complete payment via Mobile Money, card, or bank transfer\n4. Your hosting account will be activated automatically within 5 minutes\n5. Check your email for your cPanel login details\n\nIf you need help at any step, open a support ticket and our team will assist you."],
            ['title'=>'How to Log Into cPanel',                        'category'=>'Hosting & cPanel',   'content'=>"Your cPanel is the control panel for managing your website files, databases, and email accounts.\n\n1. Go to your SkyNetug dashboard\n2. Click on your hosting account\n3. Click 'Open cPanel' for direct single sign-on access\n\nAlternatively, navigate to: http://yourdomain.com:2083"],
            ['title'=>'How to Register a Domain Name',                 'category'=>'Domain Management',  'content'=>"Registering a domain with SkyNetug is simple:\n\n1. Go to skynetug.com/domains\n2. Type your desired domain name in the search box\n3. Browse available TLDs and prices\n4. Add to cart and complete payment\n5. Your domain is registered and added to your account instantly."],
            ['title'=>'Paying with MTN Mobile Money',                  'category'=>'Billing & Payments', 'content'=>"To pay with MTN Mobile Money:\n\n1. Select an invoice or go to checkout\n2. Choose 'MTN Mobile Money' as your payment method\n3. Enter your MTN phone number (format: 256700000000)\n4. A payment prompt will be sent to your phone\n5. Approve the transaction on your phone\n6. Your invoice will be marked as paid automatically within 2 minutes."],
            ['title'=>'How to Create Professional Email Accounts',     'category'=>'Email Hosting',      'content'=>"To create email accounts for your domain:\n\n1. Log into cPanel\n2. Click 'Email Accounts' under the Email section\n3. Click 'Create' and fill in the username and password\n4. Your email is ready — configure it in Outlook, Gmail, or any mail client\n\nUse these settings:\n- IMAP: mail.yourdomain.com port 993 (SSL)\n- SMTP: mail.yourdomain.com port 465 (SSL)"],
            ['title'=>'How to Install WordPress with 1-Click',        'category'=>'Hosting & cPanel',   'content'=>"SkyNetug includes Softaculous for 1-click application installs.\n\n1. Log into cPanel\n2. Click 'Softaculous Apps Installer'\n3. Click WordPress\n4. Click 'Install Now'\n5. Fill in your site name, admin username, and password\n6. Click Install — WordPress is live in under 60 seconds!"],
            ['title'=>'SSL Certificate — How It Works',                'category'=>'Security & SSL',     'content'=>"All SkyNetug hosting plans include a free Let's Encrypt SSL certificate. SSL encrypts the connection between your website and visitors, shown as a padlock in browsers.\n\nSSL certificates are installed automatically when your hosting account is created. They renew automatically every 90 days.\n\nIf your site shows 'Not Secure', please open a support ticket and we'll fix it for you."],
            ['title'=>'How to Open a Support Ticket',                  'category'=>'Getting Started',    'content'=>"If you need help, our support team is available 24/7.\n\n1. Log into your SkyNetug dashboard\n2. Click 'Support Tickets' in the sidebar\n3. Click 'New Ticket'\n4. Choose the category (Technical, Billing, Sales, General)\n5. Select priority and describe your issue\n6. Attach screenshots or logs if helpful\n7. Submit — we'll respond within 2 hours for most issues."],
        ];

        foreach ($articles as $article) {
            KnowledgeBase::create([
                'title'      => $article['title'],
                'slug'       => Str::slug($article['title']),
                'category'   => $article['category'],
                'content'    => $article['content'],
                'status'     => 'published',
                'views'      => rand(10, 200),
                'created_by' => 1,
            ]);
        }

        $this->command->info('✅ Database seeded successfully!');
        $this->command->info('   Admin login: admin@skynetug.com / Admin@1234');
        $this->command->info('   Customer:    john@example.com / Customer@1234');
    }
}
