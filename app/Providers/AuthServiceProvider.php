<?php

namespace App\Providers;

use App\Models\Buyer;
use App\Models\Product;
use App\Models\Seller;
use App\Models\Transaction;
use App\Models\User;
use App\Policies\BuyerPolicy;
use App\Policies\ProductPolicy;
use App\Policies\SellerPolicy;
use App\Policies\TransactionPolicy;
use App\Policies\UserPolicy;
use Carbon\Carbon;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        Buyer::class => BuyerPolicy::class,
        Seller::class => SellerPolicy::class,
        User::class => UserPolicy::class,
        Transaction::class => TransactionPolicy::class,
        Product::class => ProductPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url') . "/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });
//        Passport::loadKeysFrom(__DIR__ . '/../../storage/oauth');
        Passport::tokensExpireIn(Carbon::now('UTC')->addMinutes(30));
        Passport::refreshTokensExpireIn(Carbon::now('UTC')->addDays(30));
        //
        Passport::tokensCan([
            'purchase-product' => 'Allows a user to purchase products',
            'manage-product' => 'CRUD operations for products',
            'manage-account' => 'Read your account data,id,name,email if verified & if admin (cannot read password),
             modify your account data (email and password) and Cannot delete your account',
            'read-general' => 'Read general information like purchasing categories, purchased products, selling products,
            selling products, selling categories, your transactions (purchases and sales)'
        ]);

        Gate::define('admin-action', function (User $user) {
            return $user->isAdmin();
        });
    }
}
