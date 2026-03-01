<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Shop;
use App\Policies\ShopPolicy;
use App\Models\GroupProduct;
use App\Policies\GroupProductPolicy;
use App\Models\Product;
use App\Policies\ProductPolicy;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
       Gate::policy(Shop::class, ShopPolicy::class);
       Gate::policy(Product::class, ProductPolicy::class);
       Gate::policy(GroupProduct::class, GroupProductPolicy::class);
       Paginator::useBootstrapFive();
    }
}
