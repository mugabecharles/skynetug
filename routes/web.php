<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Marketing\HomeController;
use App\Http\Controllers\Marketing\HostingController;
use App\Http\Controllers\Marketing\DomainController;
use App\Http\Controllers\Marketing\PricingController;
use App\Http\Controllers\Marketing\ContactController;
use App\Http\Controllers\Dashboard\CustomerDashboardController;
use App\Http\Controllers\Dashboard\HostingAccountController;
use App\Http\Controllers\Dashboard\DomainManagementController;
use App\Http\Controllers\Dashboard\InvoiceController;
use App\Http\Controllers\Dashboard\SupportTicketController;
use App\Http\Controllers\Dashboard\AffiliateController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\ServerController;
use App\Http\Controllers\Admin\AdminInvoiceController;
use App\Http\Controllers\Admin\AdminPaymentController;
use App\Http\Controllers\Admin\AdminSupportController;
use App\Http\Controllers\Admin\AdminDomainController;
use App\Http\Controllers\Admin\AdminHostingController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\AffiliateManagementController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\KnowledgeBaseController;
use App\Http\Controllers\Payment\PaymentController;

// ============================================================
// PUBLIC MARKETING ROUTES
// ============================================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/hosting', [HostingController::class, 'index'])->name('hosting');
Route::get('/hosting/shared', [HostingController::class, 'shared'])->name('hosting.shared');
Route::get('/hosting/wordpress', [HostingController::class, 'wordpress'])->name('hosting.wordpress');
Route::get('/hosting/vps', [HostingController::class, 'vps'])->name('hosting.vps');
Route::get('/hosting/email', [HostingController::class, 'email'])->name('hosting.email');
Route::get('/domains', [DomainController::class, 'index'])->name('domains');
Route::get('/domains/search', [DomainController::class, 'search'])->name('domains.search');
Route::post('/domains/check', [DomainController::class, 'check'])->name('domains.check');
Route::get('/pricing', [PricingController::class, 'index'])->name('pricing');
Route::get('/ssl', [PricingController::class, 'ssl'])->name('ssl');
Route::get('/reseller-hosting', [PricingController::class, 'reseller'])->name('reseller');
Route::get('/website-design', [PricingController::class, 'design'])->name('design');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/knowledge-base', [KnowledgeBaseController::class, 'publicIndex'])->name('kb.index');
Route::get('/knowledge-base/{slug}', [KnowledgeBaseController::class, 'publicShow'])->name('kb.show');

// ============================================================
// AUTHENTICATION ROUTES
// ============================================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// ============================================================
// CUSTOMER DASHBOARD ROUTES
// ============================================================
Route::middleware(['auth'])->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', [CustomerDashboardController::class, 'index'])->name('index');

    // Hosting accounts
    Route::prefix('hosting')->name('hosting.')->group(function () {
        Route::get('/', [HostingAccountController::class, 'index'])->name('index');
        Route::get('/{id}', [HostingAccountController::class, 'show'])->name('show');
        Route::get('/{id}/cpanel', [HostingAccountController::class, 'cpanelSso'])->name('cpanel');
    });

    // Domain management
    Route::prefix('domains')->name('domains.')->group(function () {
        Route::get('/', [DomainManagementController::class, 'index'])->name('index');
        Route::get('/{id}', [DomainManagementController::class, 'show'])->name('show');
        Route::post('/{id}/dns', [DomainManagementController::class, 'updateDns'])->name('dns.update');
        Route::post('/{id}/nameservers', [DomainManagementController::class, 'updateNameservers'])->name('nameservers.update');
        Route::post('/{id}/lock', [DomainManagementController::class, 'toggleLock'])->name('lock.toggle');
        Route::post('/{id}/privacy', [DomainManagementController::class, 'togglePrivacy'])->name('privacy.toggle');
    });

    // Invoices
    Route::prefix('invoices')->name('invoices.')->group(function () {
        Route::get('/', [InvoiceController::class, 'index'])->name('index');
        Route::get('/{id}', [InvoiceController::class, 'show'])->name('show');
        Route::get('/{id}/pdf', [InvoiceController::class, 'downloadPdf'])->name('pdf');
        Route::post('/{id}/pay', [InvoiceController::class, 'pay'])->name('pay');
    });

    // Support tickets
    Route::prefix('tickets')->name('tickets.')->group(function () {
        Route::get('/', [SupportTicketController::class, 'index'])->name('index');
        Route::get('/create', [SupportTicketController::class, 'create'])->name('create');
        Route::post('/', [SupportTicketController::class, 'store'])->name('store');
        Route::get('/{id}', [SupportTicketController::class, 'show'])->name('show');
        Route::post('/{id}/reply', [SupportTicketController::class, 'reply'])->name('reply');
    });

    // Affiliate program
    Route::prefix('affiliate')->name('affiliate.')->group(function () {
        Route::get('/', [AffiliateController::class, 'index'])->name('index');
        Route::post('/enroll', [AffiliateController::class, 'enroll'])->name('enroll');
        Route::post('/payout', [AffiliateController::class, 'requestPayout'])->name('payout');
    });

    // Profile & settings
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::post('/update', [ProfileController::class, 'update'])->name('update');
        Route::post('/password', [ProfileController::class, 'updatePassword'])->name('password');
        Route::post('/2fa/enable', [ProfileController::class, 'enable2fa'])->name('2fa.enable');
        Route::post('/2fa/disable', [ProfileController::class, 'disable2fa'])->name('2fa.disable');
        Route::post('/notifications', [ProfileController::class, 'updateNotifications'])->name('notifications');
    });
});

// ============================================================
// CART ROUTES
// ============================================================
// Public add-to-cart — works for guests (redirects to login) and auth users
Route::post('/cart/add', [\App\Http\Controllers\Order\CartController::class, 'addPublic'])->name('cart.add.public');

// Cart view is accessible to guests (so they can see what they added before logging in)
Route::get('/cart', [\App\Http\Controllers\Order\CartController::class, 'index'])->name('cart.index');

// Domain prompt — guest accessible
Route::get('/cart/domain-prompt',  [\App\Http\Controllers\Order\CartController::class, 'domainPrompt'])->name('cart.domain.prompt');
Route::post('/cart/domain-prompt', [\App\Http\Controllers\Order\CartController::class, 'domainPromptSubmit'])->name('cart.domain.prompt.submit');

// Remove & clear — guest accessible (guests can manage their own session cart)
Route::delete('/cart/remove/{key}', [\App\Http\Controllers\Order\CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear',          [\App\Http\Controllers\Order\CartController::class, 'clear'])->name('cart.clear');

// Coupon — guest accessible
Route::post('/cart/coupon',   [\App\Http\Controllers\Order\CartController::class, 'applyCoupon'])->name('cart.coupon');
Route::delete('/cart/coupon', [\App\Http\Controllers\Order\CartController::class, 'removeCoupon'])->name('cart.coupon.remove');

Route::middleware(['auth'])->prefix('cart')->name('cart.')->group(function () {
    Route::patch('/update/{key}',        [\App\Http\Controllers\Order\CartController::class, 'update'])->name('update');
    Route::patch('/attach-domain/{key}', [\App\Http\Controllers\Order\CartController::class, 'attachDomain'])->name('attach.domain');
});
Route::middleware(['auth'])->prefix('order')->name('order.')->group(function () {
    Route::get('/hosting/{slug}', [App\Http\Controllers\Order\OrderController::class, 'hostingCheckout'])->name('hosting');
    Route::get('/domain', [App\Http\Controllers\Order\OrderController::class, 'domainCheckout'])->name('domain');
    Route::post('/checkout', [App\Http\Controllers\Order\OrderController::class, 'checkout'])->name('checkout');
    Route::get('/confirm/{order}', [App\Http\Controllers\Order\OrderController::class, 'confirm'])->name('confirm');
});

// Review page — guest accessible (shows cart summary before login prompt)
Route::get('/cart/review', [\App\Http\Controllers\Order\CartController::class, 'review'])->name('cart.review');

// ============================================================
// PAYMENT ROUTES
// ============================================================
Route::middleware(['auth'])->prefix('payment')->name('payment.')->group(function () {
    Route::post('/initiate', [PaymentController::class, 'initiate'])->name('initiate');
    Route::get('/pending', [PaymentController::class, 'pending'])->name('pending');
    Route::get('/check-status', [PaymentController::class, 'checkStatus'])->name('check-status');
    Route::get('/callback/{gateway}', [PaymentController::class, 'callback'])->name('callback');
    Route::post('/webhook/{gateway}', [PaymentController::class, 'webhook'])->name('webhook')->withoutMiddleware('auth');
    Route::get('/success', [PaymentController::class, 'success'])->name('success');
    Route::get('/failed', [PaymentController::class, 'failed'])->name('failed');
});

// ============================================================
// ADMIN ROUTES
// ============================================================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Users
    Route::resource('users', UserManagementController::class);

    // Hosting packages
    Route::resource('packages', PackageController::class);

    // Servers
    Route::resource('servers', ServerController::class);

    // Hosting accounts
    Route::prefix('hosting')->name('hosting.')->group(function () {
        Route::get('/', [AdminHostingController::class, 'index'])->name('index');
        Route::get('/{id}', [AdminHostingController::class, 'show'])->name('show');
        Route::post('/{id}/suspend', [AdminHostingController::class, 'suspend'])->name('suspend');
        Route::post('/{id}/unsuspend', [AdminHostingController::class, 'unsuspend'])->name('unsuspend');
        Route::post('/{id}/terminate', [AdminHostingController::class, 'terminate'])->name('terminate');
    });

    // Invoices
    Route::resource('invoices', AdminInvoiceController::class)->only(['index', 'show']);
    Route::post('invoices/{id}/refund', [AdminInvoiceController::class, 'refund'])->name('invoices.refund');

    // Payments
    Route::get('payments', [AdminPaymentController::class, 'index'])->name('payments.index');

    // Support
    Route::prefix('tickets')->name('tickets.')->group(function () {
        Route::get('/', [AdminSupportController::class, 'index'])->name('index');
        Route::get('/{id}', [AdminSupportController::class, 'show'])->name('show');
        Route::post('/{id}/reply', [AdminSupportController::class, 'reply'])->name('reply');
        Route::post('/{id}/assign', [AdminSupportController::class, 'assign'])->name('assign');
        Route::post('/{id}/close', [AdminSupportController::class, 'close'])->name('close');
        Route::post('/{id}/escalate', [AdminSupportController::class, 'escalate'])->name('escalate');
    });

    // Domains
    Route::get('domains', [AdminDomainController::class, 'index'])->name('domains.index');
    Route::get('domains/{id}', [AdminDomainController::class, 'show'])->name('domains.show');

    // Coupons
    Route::resource('coupons', CouponController::class);

    // Domain TLD Pricing
    Route::resource('tld-pricing', \App\Http\Controllers\Admin\TldPricingController::class)->parameters(['tld-pricing' => 'tldPricing']);
    Route::post('tld-pricing/bulk-update', [\App\Http\Controllers\Admin\TldPricingController::class, 'bulkUpdate'])->name('tld-pricing.bulk-update');

    // Affiliates
    Route::prefix('affiliates')->name('affiliates.')->group(function () {
        Route::get('/', [AffiliateManagementController::class, 'index'])->name('index');
        Route::post('/{id}/approve-payout', [AffiliateManagementController::class, 'approvePayout'])->name('payout.approve');
    });

    // Announcements
    Route::resource('announcements', AnnouncementController::class);

    // Knowledge base
    Route::resource('kb', KnowledgeBaseController::class)->except(['show'])->parameters(['kb' => 'kb']);

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/sales', [ReportController::class, 'sales'])->name('sales');
        Route::get('/payments', [ReportController::class, 'payments'])->name('payments');
        Route::get('/customers', [ReportController::class, 'customers'])->name('customers');
        Route::get('/hosting', [ReportController::class, 'hosting'])->name('hosting');
        Route::get('/domains', [ReportController::class, 'domains'])->name('domains');
        Route::get('/support', [ReportController::class, 'support'])->name('support');
        Route::get('/affiliates', [ReportController::class, 'affiliates'])->name('affiliates');
        Route::get('/renewals', [ReportController::class, 'renewals'])->name('renewals');
        Route::get('/tax', [ReportController::class, 'tax'])->name('tax');
        Route::post('/export', [ReportController::class, 'export'])->name('export');
    });

    // Audit logs
    Route::get('audit-logs', [AdminDashboardController::class, 'auditLogs'])->name('audit-logs');
});
