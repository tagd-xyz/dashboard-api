<?php

namespace App\Providers;

use App\Support\FirebaseToken;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tagd\Core\Models\Actor\Reseller;
use Tagd\Core\Models\Actor\Retailer;
use Tagd\Core\Models\User\Role;
use Tagd\Core\Repositories\Interfaces\Users\Users;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \Tagd\Core\Models\Item\Item::class => \App\Policies\Item\Item::class,
        \Tagd\Core\Models\Item\Tagd::class => \App\Policies\Item\Tagd::class,
        \Tagd\Core\Models\Item\Tagd::class => \App\Policies\Item\Tagd::class,
        \Tagd\Core\Models\Resale\AccessRequest::class => \App\Policies\Resale\AccessRequest::class,
        \Tagd\Core\Models\Actor\Reseller::class => \App\Policies\Actor\Reseller::class,
        \Tagd\Core\Models\Actor\Retailer::class => \App\Policies\Actor\Retailer::class,
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(Users $users)
    {
        $this->registerPolicies();

        Auth::viaRequest('firebase', function (Request $request) use ($users) {
            $projectId = config('services.firebase.project_id');
            $tenantIdReseller = config('services.firebase.tenant_id_resellers');
            $tenantIdRetailer = config('services.firebase.tenant_id_retailers');

            $token = $request->bearerToken();

            if ($token) {
                try {
                    $payload = (new FirebaseToken($token))->verify($projectId);

                    if ($tenantIdReseller == $payload->firebase->tenant) {
                        $user = $users->createFromFirebaseToken($payload);
                        $user->tenant = Role::RESELLER;
                        $users->assertIsActingAs($user, Reseller::class);

                        return $user;
                    } elseif ($tenantIdRetailer == $payload->firebase->tenant) {
                        $user = $users->createFromFirebaseToken($payload);
                        $user->tenant = Role::RETAILER;
                        $users->assertIsActingAs($user, Retailer::class);

                        return $user;
                    }
                } catch (\Exception $e) {
                    \Log::error('Invalid bearer token: ' . $e->getMessage());

                    return null;
                }
            }

            return null;
        });
    }
}
