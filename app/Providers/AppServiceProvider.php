<?php

namespace App\Providers;

use App\Models\Conversation;
use App\Models\Project;
use App\Models\User;
use App\Policies\ConversationPolicy;
use App\Policies\IsAdmin;
use App\Policies\IsAdminPolicy;
use App\Policies\ProjectPolicy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

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

        Gate::policy(Conversation::class, ConversationPolicy::class);

        // check if the login user is admin
        Gate::define("IsAdmin", function () {
            $user = Auth::user();
            return $user->isAdmin();
        });
        // check if user is expert
        Gate::define("IsExpert", function () {
            $user = Auth::user();
            return $user->isExpert();
        }
        );
        // pass the id of the user which you want to check if its the same login user
        Gate::define("IsSameUser", function (User $user, $id) {
            return Auth::id() == $id;
        });


    }
}
