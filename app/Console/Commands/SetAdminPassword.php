<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SetAdminPassword extends Command
{
    protected $signature = 'admin:password {email=admin@gitinfosys.com}';

    protected $description = 'Set a new random password for an admin account, show it once and sign the account out everywhere';

    public function handle(): int
    {
        $user = User::where('email', $this->argument('email'))->where('is_admin', true)->first();
        if (! $user) {
            $this->error('No admin account with that e-mail.');

            return self::FAILURE;
        }

        // 20 random characters, always with lower case, upper case and a digit.
        do {
            $password = Str::random(20);
        } while (! preg_match('/[a-z]/', $password) || ! preg_match('/[A-Z]/', $password) || ! preg_match('/\d/', $password));

        $user->forceFill(['password' => Hash::make($password), 'remember_token' => null])->save();
        DB::table('sessions')->where('user_id', $user->id)->delete();

        $this->info("New password for {$user->email}: {$password}");

        return self::SUCCESS;
    }
}
