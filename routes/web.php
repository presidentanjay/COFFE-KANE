<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\IsAdmin;

// Global Controllers
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\MiningController;
use App\Http\Controllers\AssociationRuleController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PromoTrackingController;
use App\Http\Controllers\Admin\PromoStatistikController;

use App\Models\Product;
use App\Models\Category;

// Admin Controllers
use App\Http\Controllers\Admin\{
    AdminController,
    UserController,
    RoleController,
    AdminTransactionController,
    AdminReportController,
    PromoController,
    CategoryController,
    PromoGenerateController
};

// Customer Controllers
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Customer\TransactionController as CustomerTransactionController;
use App\Http\Controllers\Customer\PromoController as CustomerPromoController;

// Kasir Controllers
use App\Http\Controllers\Kasir\KasirController;

// ===============================
// Middleware Alias
// ===============================
Route::aliasMiddleware('role', RoleMiddleware::class);

// ===============================
// PUBLIC ROUTES (No Auth Required)
// ===============================

// Landing Page
Route::get('/', function () {
    $categoryId = request('category');
    
    $products = Product::with('category')
        ->when($categoryId, function ($query, $categoryId) {
            $query->where('category_id', $categoryId);
        })
        ->get();

    $categories = Category::all();

    return view('welcome', compact('products', 'categories'));
});

// Public Pages
Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/promo', function () {
    return view('promo');
})->name('promo');

Route::get('/reservasi', function () {
    return view('reservasi');
})->name('reservasi');

// Public Payment & Receipt Routes
Route::get('/kasir/transactions/{transaction}/receipt/pdf', [KasirController::class, 'printPDF'])->name('kasir.transactions.receipt.pdf');
Route::post('/payment/{transaction}', [PaymentController::class, 'createPayment'])->name('payment.create');
Route::post('/payment/notification', [PaymentController::class, 'notification'])->name('payment.notification');

// Public Tracking Routes
Route::prefix('tracking')->name('tracking.')->group(function () {
    Route::post('/view/{id}', [App\Http\Controllers\TrackingController::class, 'trackView'])->name('view');
    Route::post('/order/{id}', [App\Http\Controllers\TrackingController::class, 'trackOrder'])->name('order');
    Route::post('/like/{id}', [App\Http\Controllers\TrackingController::class, 'trackLike'])->name('like');
});

Route::post('/promo/{id}/track-view', [PromoTrackingController::class, 'trackView']);
Route::post('/promo/{id}/track-like', [PromoTrackingController::class, 'trackLike']);
Route::post('/promo/{id}/track-order', [PromoTrackingController::class, 'trackOrder']);

// ===============================
// AUTHENTICATED ROUTES (Login Required)
// ===============================
Route::middleware('auth')->group(function () {

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Shared Product Routes (All Roles)
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

    // Cart Routes
    Route::post('/cart/add/{productId}', [CartController::class, 'tambah'])->name('cart.add');
    Route::get('/cart', [CartController::class, 'show'])->name('customer.cart');
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('customer.cart.checkout');
    Route::get('/cart/hapus/{id}', [CartController::class, 'hapus'])->name('customer.cart.hapus');
    Route::post('/cart/tambah-promo', [CartController::class, 'tambahPromo'])->name('customer.cart.tambah.promo');

    // ===============================
    // ADMIN ROUTES
    // ===============================
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        
        // Resource Routes
        Route::resource('users', UserController::class);
        Route::resource('roles', RoleController::class);
        Route::resource('products', ProductController::class);
        Route::resource('transactions', AdminTransactionController::class);
        Route::resource('promos', PromoController::class)->except(['show']);
        Route::resource('categories', CategoryController::class);

        // Reports
        Route::get('reports', [AdminReportController::class, 'index'])->name('reports.index');
        Route::get('reports/export', [AdminReportController::class, 'export'])->name('reports.export');

        // Mining & Association Rules
        Route::get('run-mining', [MiningController::class, 'runMining'])->name('mining.run');
        Route::resource('association-rules', AssociationRuleController::class)->only(['index', 'update']);
        Route::post('/association-rules/{id}/toggle', [AssociationRuleController::class, 'toggle'])->name('rules.toggle');

        // Promo Management
        Route::prefix('promos')->name('promos.')->group(function () {
            Route::get('/import', [PromoController::class, 'showImportForm'])->name('import.form');
            Route::post('/import', [PromoController::class, 'import'])->name('import');
            Route::post('/import/upload', [PromoController::class, 'handleImport'])->name('handleImport');
            Route::get('/export', [PromoController::class, 'export'])->name('export');
            Route::get('/stats', [PromoStatistikController::class, 'index'])->name('stats');
            Route::get('/generate', [PromoGenerateController::class, 'generate'])->name('generate');
            Route::post('/generate', [PromoGenerateController::class, 'generate'])->name('generate.post');
            Route::post('/upload', [PromoGenerateController::class, 'upload'])->name('upload');
            
            // Promo Actions
            Route::patch('/{id}/toggle', [PromoController::class, 'toggle'])->name('toggle');
            Route::post('/{id}/activate', [PromoController::class, 'activate'])->name('activate');
            Route::post('/{id}/deactivate', [PromoController::class, 'deactivate'])->name('deactivate');
            Route::put('/{id}/updateDiscount', [PromoController::class, 'updateDiscount'])->name('updateDiscount');
            Route::post('/activate-all', [PromoController::class, 'activateAll'])->name('activateAll');
            Route::post('/deactivate-all', [PromoController::class, 'deactivateAll'])->name('deactivateAll');
        });
    });

    // ===============================
    // KASIR ROUTES
    // ===============================
    Route::middleware('role:kasir')->prefix('kasir')->name('kasir.')->group(function () {
        Route::get('/dashboard', [KasirController::class, 'index'])->name('dashboard');
        
        // Transactions
        Route::get('/transactions', [KasirController::class, 'transactions'])->name('transactions.index');
        Route::get('/transactions/create', [KasirController::class, 'createManual'])->name('transactions.create');
        Route::post('/transactions', [KasirController::class, 'store'])->name('transactions.store');
        Route::get('/transactions/{transaction}', [KasirController::class, 'show'])->name('transactions.show');
        Route::get('/transactions/{transaction}/receipt', [KasirController::class, 'printReceipt'])->name('transactions.receipt');
        Route::post('/transactions/{transaction}/confirm', [KasirController::class, 'confirmTransaction'])->name('transactions.confirm');
        Route::post('/transactions/{transaction}/status', [KasirController::class, 'updateStatus'])->name('transactions.updateStatus');

        // Other Pages
        Route::get('/reports', fn() => view('kasir.reports.index'))->name('reports.index');
        Route::get('/staff', fn() => view('kasir.staff.index'))->name('staff.index');
        Route::get('/absen', fn() => view('kasir.attendance.index'))->name('attendance.index');
    });

    // ===============================
    // CUSTOMER ROUTES
    // ===============================
    Route::middleware('role:customer')->prefix('customer')->name('customer.')->group(function () {
        Route::get('/dashboard', [CustomerController::class, 'index'])->name('dashboard');

        // Transactions
        Route::get('/transactions/create', [CustomerTransactionController::class, 'create'])->name('transactions.create');
        Route::post('/transactions/order', [CustomerTransactionController::class, 'order'])->name('transactions.order');
        Route::post('/transactions/checkout', [CustomerTransactionController::class, 'checkout'])->name('transactions.checkout');
        Route::delete('/cart/{id}/remove', [CustomerTransactionController::class, 'remove'])->name('cart.remove');
        Route::get('/transactions/history', [CustomerTransactionController::class, 'history'])->name('transactions.history');
        Route::get('/transactions/{transaction}', [CustomerTransactionController::class, 'show'])->name('transactions.show');
        Route::get('/transactions/{transaction}/receipt', [CustomerTransactionController::class, 'receiptPdf'])->name('transactions.receipt.pdf');

        // Promo & Bundling
        Route::get('/promo', [CustomerPromoController::class, 'index'])->name('promos.index');
        Route::get('/promos/{id}/products', [CustomerPromoController::class, 'getPromoProducts'])->name('promos.products');
        Route::post('/order-bundle', [CustomerController::class, 'orderBundle'])->name('transactions.orderBundle');
        Route::post('/dashboard/bundling', [CustomerPromoController::class, 'orderBundle'])->name('transactions.orderBundle.dashboard');

        // Reviews
        Route::post('/reviews', [ReviewController::class, 'store'])->name('review.store');

        // Reservation
        Route::get('/reservation', fn() => view('customer.reservation.index'))->name('reservation.index');
    });

    // ===============================
    // SHARED DASHBOARD ROUTE
    // ===============================
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Recommendation Route
    Route::get('/rekomendasi', [CustomerController::class, 'rekomendasiProduk'])->name('rekomendasi');
    Route::get('/tes-history', [CustomerTransactionController::class, 'history']);
});

require __DIR__.'/auth.php';