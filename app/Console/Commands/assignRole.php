<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
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
        // find me
        $user = User::where('email', 'juan@totally.group')->first();

        // find retailer
        $ret = Retailer::first();

        $role = Role::firstOrCreate([
            'user_id' => $user->id,
            'actor_id' => $ret->id,
            'actor_type' => Role::RETAILER,
        ]);

        return Command::SUCCESS;
    }
}
