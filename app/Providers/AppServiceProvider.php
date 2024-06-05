<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('role', function ($role) {
            return "<?php if(auth()->user()->hasRole({$role})): ?>";
        });

        Blade::directive('endrole', function () {
            return '<?php endif; ?>';
        });
        Validator::extend('exists_in_users_or_admins', function ($attribute, $value, $parameters, $validator) {
            // Check if the email exists in either users or admins table
            $userExists = DB::table('users')->where('email', $value)->exists();
            $adminExists = DB::table('admins')->where('email', $value)->exists();
            return $userExists || $adminExists;
        });
    }
}
