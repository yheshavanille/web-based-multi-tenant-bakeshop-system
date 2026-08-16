<?php

use App\Livewire\Admin\AdminDashboard;
use App\Livewire\Admin\Pages\Role\CreateRole;
use App\Livewire\Admin\Pages\Role\EditRole;
use App\Livewire\Admin\Pages\Role\ViewRole;
use App\Livewire\Admin\Pages\Shops\ShopDetails;
use App\Livewire\Admin\Pages\Shops\ViewShops;
use App\Livewire\Admin\Pages\Users\CreateUser;
use App\Livewire\Admin\Pages\Users\EditUser;
use App\Livewire\Admin\Pages\Users\ViewUser;
use App\Livewire\Admin\PublicPages\AdminAboutUs;
use App\Livewire\Admin\PublicPages\AdminTeams;
use App\Livewire\Admin\PublicPages\Teams;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Logout;
use App\Livewire\Auth\Register;
use App\Livewire\Customer\BrowseShops;
use App\Livewire\Customer\Dashboard as CustomerDashboard;
use App\Livewire\Customer\PublicPages\AboutUs;
use App\Livewire\Customer\PublicPages\CustomerAboutUs;
use App\Livewire\Customer\PublicPages\CustomerTeams;
use App\Livewire\Customer\ViewProducts;
use App\Livewire\Owner\Branches\ManageBranches;
use App\Livewire\Owner\Branches\ManageBranchCards;
use App\Livewire\Owner\Category\CreateCategory;
use App\Livewire\Owner\Category\EditCategory;
use App\Livewire\Owner\Category\ViewCategory;
use App\Livewire\Owner\Dashboard as OwnerDashboard;
use App\Livewire\Owner\Employees\ManageEmployees;
use App\Livewire\Owner\Products\CreateProduct;
use App\Livewire\Owner\Products\EditProduct;
use App\Livewire\Owner\Products\ViewProduct;
use App\Livewire\Owner\PublicPages\OwnerAboutUs;
use App\Livewire\Owner\PublicPages\OwnerTeams;
use App\Livewire\Owner\Shop\EditShop;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('livewire.auth.login');
});

Route::get('/login', Login::class)->name('livewire.auth.login');
Route::get('/register', Register::class)->name('livewire.auth.register');
Route::get('/logout', Logout::class)->name('livewire.auth.logout');

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('livewire.auth.login');
})->name('logout.post')->middleware('auth');

// ==================== ADMIN ROUTES ====================
Route::prefix('admin')
    ->middleware(['auth', 'role:super_admin'])
    ->group(function () {
        Route::get('/dashboard', AdminDashboard::class)->name('livewire.admin.admin-dashboard');

        // shops
        Route::get('/shops', ViewShops::class)->name('livewire.admin.pages.shops.view-shops');
        Route::get('/shops/shopdetails/{shopId}', ShopDetails::class)->name('livewire.admin.pages.shops.shop-details');

        // public pages
        Route::get('/admin-about-us', AdminAboutUs::class)->name('livewire.admin.public-pages.admin-about-us');
        Route::get('/admin-teams', AdminTeams::class)->name('livewire.admin.public-pages.admin-teams');

        // pending sellers
        Route::get('/pending-sellers', \App\Livewire\Admin\PendingSellers::class)->name('livewire.admin.pending-sellers');
    });

// ==================== OWNER ROUTES ====================
Route::prefix('owner')
    ->middleware(['auth', 'role:owner'])
    ->group(function () {
        Route::get('/dashboard', OwnerDashboard::class)->name('livewire.owner.dashboard');

        // products - with branch filter support
        Route::get('/products/{branch?}', ViewProduct::class)->name('livewire.owner.products.view-product');
        Route::get('/products/create', CreateProduct::class)->name('livewire.owner.products.create-product');
        Route::get('/products/edit/{productId}', EditProduct::class)->name('livewire.owner.products.edit-product');

        // shop
        Route::get('/shop/edit', EditShop::class)->name('livewire.owner.shop.edit-shop');

        // branches
        Route::get('/branches', ManageBranches::class)->name('livewire.owner.branches.manage-branches');
        Route::get('/branches/cards', ManageBranchCards::class)->name('livewire.owner.branches.manage-cards');

        // category
        Route::get('/categories', ViewCategory::class)->name('livewire.owner.category.view-category');
        Route::get('/categories/create', CreateCategory::class)->name('livewire.owner.category.create-category');
        Route::get('/categories/edit/{categoryId}', EditCategory::class)->name('livewire.owner.category.edit-category');

        // employees - with branch filter support
        Route::get('/employees/{branch?}', ManageEmployees::class)->name('livewire.owner.employees.manage');

        // public pages
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
        Route::get('/order-confirmation/{order}', \App\Livewire\Customer\OrderConfirmation::class)->name('livewire.customer.order-confirmation');
        Route::get('/orders', \App\Livewire\Customer\Orders::class)->name('livewire.customer.orders');

        Route::get('/start-selling', \App\Livewire\Customer\StartSelling::class)->name('livewire.customer.start-selling');
        Route::get('/seller-registration', \App\Livewire\Customer\SellerRegistration::class)->name('livewire.customer.seller-registration');

        // public pages
        Route::get('/customer-about-us', \App\Livewire\Customer\PublicPages\CustomerAboutUs::class)->name('livewire.customer.public-pages.customer-about-us');
        Route::get('/customer-teams', \App\Livewire\Customer\PublicPages\CustomerTeams::class)->name('livewire.customer.public-pages.customer-teams');
    });

// ==================== EMPLOYEE ROUTES ====================
Route::prefix('employee')
    ->middleware(['auth', 'employee'])
    ->group(function () {
        Route::get('/dashboard', \App\Livewire\Employee\Dashboard::class)->name('livewire.employee.dashboard');
        Route::get('/products', \App\Livewire\Employee\Products::class)->name('livewire.employee.products');
        Route::get('/orders', \App\Livewire\Employee\Orders::class)->name('livewire.employee.orders');
    });
