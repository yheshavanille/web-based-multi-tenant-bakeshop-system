<?php

use App\Livewire\Admin\AdminDashboard;
use App\Livewire\Admin\Pages\Role\CreateRole;
use App\Livewire\Admin\Pages\Role\EditRole;
use App\Livewire\Admin\Pages\Role\ViewRole;
use App\Livewire\Admin\Pages\Shops\ShopDetails;
use App\Livewire\Admin\Pages\Shops\ViewShops;
use App\Livewire\Admin\Pages\Users\CreateUser;
use App\Livewire\Admin\Pages\Users\EditUser;
use App\Livewire\Admin\Pages\Users\ManageUsers;
use App\Livewire\Admin\Pages\Users\ViewUser;
use App\Livewire\Admin\PublicPages\Teams;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Logout;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\ResetPasswordPage;
use App\Livewire\Auth\VerifyOtp;
use App\Livewire\Customer\BrowseShops;
use App\Livewire\Customer\Dashboard as CustomerDashboard;
use App\Livewire\Customer\PublicPages\AboutUs;
use App\Livewire\Customer\PublicPages\CustomerAboutUs;
use App\Livewire\Customer\PublicPages\CustomerTeams;
use App\Livewire\Customer\ViewProducts;
use App\Livewire\Employee\Products;
use App\Livewire\Owner\Branches\BranchOrders;
use App\Livewire\Owner\Branches\ManageBranchCards;
use App\Livewire\Owner\Branches\ManageBranches;
use App\Livewire\Owner\Category\CreateCategory;
use App\Livewire\Owner\Category\EditCategory;
use App\Livewire\Owner\Category\ViewCategory;
use App\Livewire\Owner\Dashboard as OwnerDashboard;
use App\Livewire\Owner\Employees\ManageEmployees;
use App\Livewire\Owner\ProductHistory;
use App\Livewire\Owner\Products\CreateProduct;
use App\Livewire\Owner\Products\EditProduct;
use App\Livewire\Owner\Products\ViewProduct;
use App\Livewire\Owner\PublicPages\OwnerAboutUs;
use App\Livewire\Owner\PublicPages\OwnerTeams;
use App\Livewire\Owner\ReviewsHistory;
use App\Livewire\Owner\Shop\EditShop;
use App\Livewire\Owner\StockUpdateHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// ✅ GUEST ROUTES (No auth required)
Route::get('/', \App\Livewire\Guest\BrowseShops::class)->name('livewire.guest.browse-shops');
Route::get('/guest/shops/{shopId}/products/{branch?}', \App\Livewire\Guest\ViewProducts::class)->name('livewire.guest.view-products');
Route::get('/guest/start-selling', \App\Livewire\Guest\StartSelling::class)->name('livewire.guest.start-selling');
Route::get('/guest/seller-registration', \App\Livewire\Guest\SellerRegistration::class)->name('livewire.guest.seller-registration');

// ✅ AUTH ROUTES
Route::get('/login', Login::class)->name('livewire.auth.login');
Route::get('/register', Register::class)->name('livewire.auth.register');
Route::get('/logout', Logout::class)->name('livewire.auth.logout');

// ✅ Forgot / Reset Password (OTP flow)
Route::get('/forgot-password', ForgotPassword::class)->name('livewire.auth.forgot-password');
Route::get('/verify-otp', VerifyOtp::class)->name('livewire.auth.verify-otp');

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('livewire.guest.browse-shops');
})->name('logout.post')->middleware('auth');

// ✅ Webhook Routes (No auth required - PayMongo calls this)
Route::post('/api/paymongo/webhook', [App\Http\Controllers\PaymentWebhookController::class, 'handle'])
    ->name('paymongo.webhook');

// ==================== ADMIN ROUTES ====================
Route::prefix('admin')
    ->middleware(['auth', 'role:super_admin'])
    ->group(function () {
        Route::get('/dashboard', AdminDashboard::class)->name('livewire.admin.admin-dashboard');
        Route::get('/shops', ViewShops::class)->name('livewire.admin.pages.shops.view-shops');
        Route::get('/shops/shopdetails/{shopId}', ShopDetails::class)->name('livewire.admin.pages.shops.shop-details');
        Route::get('/users', ManageUsers::class)->name('livewire.admin.pages.users.manage-users');
        Route::get('/pending-sellers', \App\Livewire\Admin\PendingSellers::class)->name('livewire.admin.pending-sellers');

        // ✅ NEW: Employee Activities (all shops)
        Route::get('/employee-activities', \App\Livewire\Admin\EmployeeActivities::class)->name('livewire.admin.employee-activities');

        // ✅ NEW: Super Admin Profile
        Route::get('/profile', \App\Livewire\Admin\Profile::class)->name('livewire.admin.profile');
    });

// ==================== OWNER ROUTES ====================
Route::prefix('owner')
    ->middleware(['auth', 'role:owner'])
    ->group(function () {
        Route::get('/dashboard', OwnerDashboard::class)->name('livewire.owner.dashboard');
        Route::get('/products/{branch?}', ViewProduct::class)->name('livewire.owner.products.view-product');
        Route::get('/products/create', CreateProduct::class)->name('livewire.owner.products.create-product');
        Route::get('/products/edit/{productId}', EditProduct::class)->name('livewire.owner.products.edit-product');
        Route::get('/shop/edit', EditShop::class)->name('livewire.owner.shop.edit-shop');
        Route::get('/branches', ManageBranches::class)->name('livewire.owner.branches.manage-branches');
        Route::get('/branches/cards', ManageBranchCards::class)->name('livewire.owner.branches.manage-cards');
        Route::get('/branch-orders/{branchId}', BranchOrders::class)->name('livewire.owner.branches.branch-orders');
        Route::get('/categories', ViewCategory::class)->name('livewire.owner.category.view-category');
        Route::get('/categories/create', CreateCategory::class)->name('livewire.owner.category.create-category');
        Route::get('/categories/edit/{categoryId}', EditCategory::class)->name('livewire.owner.category.edit-category');
        Route::get('/employees/{branch?}', ManageEmployees::class)->name('livewire.owner.employees.manage');
        Route::get('/reviews-history', ReviewsHistory::class)->name('livewire.owner.reviews-history');
        Route::get('/product-history', ProductHistory::class)->name('livewire.owner.product-history');
        Route::get('/stock-history', StockUpdateHistory::class)->name('livewire.owner.stock-history');
        Route::get('/employee-activities', \App\Livewire\Owner\EmployeeActivities::class)->name('livewire.owner.employee-activities');
        Route::get('/owner-about-us', OwnerAboutUs::class)->name('livewire.owner.public-pages.owner-about-us');
        Route::get('/owner-teams', OwnerTeams::class)->name('livewire.owner.public-pages.owner-teams');
    });

// ==================== CUSTOMER ROUTES ====================
Route::prefix('customer')
    ->middleware(['auth', 'role:customer'])
    ->group(function () {
        Route::get('/dashboard', \App\Livewire\Customer\Dashboard::class)->name('livewire.customer.dashboard');
        Route::get('/profile', \App\Livewire\Customer\Profile::class)->name('livewire.customer.profile');
        Route::get('/shops', \App\Livewire\Customer\BrowseShops::class)->name('livewire.customer.browse-shops');
        Route::get('/shops/{shopId}/products/{branch?}', \App\Livewire\Customer\ViewProducts::class)->name('livewire.customer.view-products');
        Route::get('/cart', \App\Livewire\Customer\Cart::class)->name('livewire.customer.cart');
        Route::get('/checkout', \App\Livewire\Customer\Checkout::class)->name('livewire.customer.checkout');
        Route::get('/order-confirmation/{order?}', \App\Livewire\Customer\OrderConfirmation::class)->name('livewire.customer.order-confirmation');
        Route::get('/orders', \App\Livewire\Customer\Orders::class)->name('livewire.customer.orders');
        Route::get('/start-selling', \App\Livewire\Customer\StartSelling::class)->name('livewire.customer.start-selling');
        Route::get('/seller-registration', \App\Livewire\Customer\SellerRegistration::class)->name('livewire.customer.seller-registration');
        Route::get('/customer-about-us', \App\Livewire\Customer\PublicPages\CustomerAboutUs::class)->name('livewire.customer.public-pages.customer-about-us');
        Route::get('/customer-teams', \App\Livewire\Customer\PublicPages\CustomerTeams::class)->name('livewire.customer.public-pages.customer-teams');
    });

// ==================== EMPLOYEE ROUTES ====================
Route::prefix('employee')
    ->middleware(['auth', 'employee'])
    ->group(function () {
        Route::get('/dashboard', \App\Livewire\Employee\Dashboard::class)->name('livewire.employee.dashboard');

        // ✅ Employee Profile
        Route::get('/profile', \App\Livewire\Employee\Profile::class)->name('livewire.employee.profile');
        Route::get('/orders', \App\Livewire\Employee\Orders::class)
            ->name('livewire.employee.orders')
            ->middleware('employee.role:order_manager');
        Route::get('/products', \App\Livewire\Employee\Products::class)
            ->name('livewire.employee.products')
            ->middleware('employee.role:order_manager');
        Route::get('/inventory', \App\Livewire\Employee\ManageStock::class)
            ->name('livewire.employee.inventory')
            ->middleware('employee.role:inventory_manager');
        Route::get('/stock-history', \App\Livewire\Employee\StockEditHistory::class)
            ->name('livewire.employee.stock-history')
            ->middleware('employee.role:inventory_manager');
    });

// ✅ Payment success — NOW ALSO CLEARS THE CART (post-payment)
Route::get('/payment/success', function () {
    // ✅ Delete the cart items that were just paid for
    $pendingCartIds = session()->pull('pending_cart_clear', []);

    if (!empty($pendingCartIds) && auth()->check()) {
        \App\Models\Cart::where('user_id', auth()->id())
            ->whereIn('id', $pendingCartIds)
            ->delete();
    }

    // ✅ Clear the checkout selection
    session()->forget('checkout_items');

    session()->flash('order_success', 'Payment successful! Your order is now being prepared.');
    return redirect()->route('livewire.customer.orders');
})->name('payment.success');

// ✅ Payment cancel — keep cart intact, but clear the checkout selection
Route::get('/payment/cancel', function () {
    session()->forget('checkout_items');
    session()->forget('pending_cart_clear');

    session()->flash('error', 'Payment was cancelled. You can try again from your cart.');
    return redirect()->route('livewire.customer.cart');
})->name('payment.cancel');
