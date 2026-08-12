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
use App\Livewire\Owner\Category\CreateCategory;
use App\Livewire\Owner\Category\EditCategory;
use App\Livewire\Owner\Category\ViewCategory;
use App\Livewire\Owner\Dashboard as OwnerDashboard;
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

Route::prefix('admin')
    ->middleware(['auth', 'role:super_admin'])
    ->group(function () {
        Route::get('/dashboard', AdminDashboard::class)->name('livewire.admin.admin-dashboard');

        // users
        Route::get('/users', ViewUser::class)->name('livewire.admin.pages.users.view-user');
        Route::get('/users/create', CreateUser::class)->name('livewire.admin.pages.users.create-user');
        Route::get('/users/edit/{userId}', EditUser::class)->name('livewire.admin.pages.users.edit-user');

        // roles
        Route::get('/roles', ViewRole::class)->name('livewire.admin.pages.role.view-role');
        Route::get('/roles/create', CreateRole::class)->name('livewire.admin.pages.role.create-role');
        Route::get('/roles/edit/{role}', EditRole::class)->name('livewire.admin.pages.role.edit-role');

        // shops
        Route::get('/shops', ViewShops::class)->name('livewire.admin.pages.shops.view-shops');
        Route::get('/shops/shopdetails/{shopId}', ShopDetails::class)->name('livewire.admin.pages.shops.shop-details');

        // public pages
        Route::get('/admin-about-us', AdminAboutUs::class)->name('livewire.admin.public-pages.admin-about-us');
        Route::get('/admin-teams', AdminTeams::class)->name('livewire.admin.public-pages.admin-teams');
    });

Route::prefix('owner')
    ->middleware(['auth', 'role:owner'])
    ->group(function () {
        Route::get('/dashboard', OwnerDashboard::class)->name('livewire.owner.dashboard');

        // products
        Route::get('/products', ViewProduct::class)->name('livewire.owner.products.view-product');
        Route::get('/products/create', CreateProduct::class)->name('livewire.owner.products.create-product');
        Route::get('/products/edit/{productId}', EditProduct::class)->name('livewire.owner.products.edit-product');

        // shop
        Route::get('/shop/edit', EditShop::class)->name('livewire.owner.shop.edit-shop');

        // branches
        Route::get('/branches', ManageBranches::class)->name('livewire.owner.branches.manage-branches');

        // category
        Route::get('/categories', ViewCategory::class)->name('livewire.owner.category.view-category');
        Route::get('/categories/create', CreateCategory::class)->name('livewire.owner.category.create-category');
        Route::get('/categories/edit/{categoryId}', EditCategory::class)->name('livewire.owner.category.edit-category');

        // public pages
        Route::get('/owner-about-us', OwnerAboutUs::class)->name('livewire.owner.public-pages.owner-about-us');
        Route::get('/owner-teams', OwnerTeams::class)->name('livewire.owner.public-pages.owner-teams');
    });

Route::prefix('customer')
    ->middleware(['auth', 'role:customer'])
    ->group(function () {
        Route::get('/dashboard', \App\Livewire\Customer\Dashboard::class)->name('livewire.customer.dashboard');
        Route::get('/profile', \App\Livewire\Customer\Profile::class)->name('livewire.customer.profile');
        Route::get('/shops', \App\Livewire\Customer\BrowseShops::class)->name('livewire.customer.browse-shops');
        Route::get('/shops/{shopId}/products', \App\Livewire\Customer\ViewProducts::class)->name('livewire.customer.view-products');
        Route::get('/cart', \App\Livewire\Customer\Cart::class)->name('livewire.customer.cart');
        Route::get('/customer-about-us', \App\Livewire\Customer\PublicPages\CustomerAboutUs::class)->name('livewire.customer.public-pages.customer-about-us');
        Route::get('/customer-teams', \App\Livewire\Customer\PublicPages\CustomerTeams::class)->name('livewire.customer.public-pages.customer-teams');
    });
