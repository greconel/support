<?php

namespace App\Jobs\DataImport;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ImportUsersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $users = DB::connection('old_ampp')->table('users')->get()->toArray();

        foreach ($users as $user){
            User::unguard();

            $newUser = User::create([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'password' => $user->password,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at
            ]);

            User::reguard();

            if ($newUser->email == 'admin@bmksolutions.be'){
                $newUser->assignRole('super admin');
                $newUser->update(['password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi']);
                continue;
            }

            $newUser->assignRole('ampp user');
        }
    }
}
