<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Tagd\Core\Models\Actor\Reseller;
use Tagd\Core\Models\Actor\Retailer;
use Tagd\Core\Models\User\Role;
use Tagd\Core\Models\User\User;

class assignRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assignRole';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign Role';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // find retailer
        $user = User::where('firebase_tenant', config('services.firebase.tenant_id_retailers'))
            ->where('email', 'juan@totally.group')->first();

        $ret = Retailer::first();

        $role = Role::firstOrCreate([
            'user_id' => $user->id,
            'actor_id' => $ret->id,
            'actor_type' => Role::RETAILER,
        ]);

        // find reseller
        $user = User::where('firebase_tenant', config('services.firebase.tenant_id_resellers'))
            ->where('email', 'juan@totally.group')->first();

        $res = Reseller::first();

        $role = Role::firstOrCreate([
            'user_id' => $user->id,
            'actor_id' => $res->id,
            'actor_type' => Role::RESELLER,
        ]);

        return Command::SUCCESS;
    }
}
