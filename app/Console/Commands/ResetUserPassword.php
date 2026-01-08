<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class ResetUserPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:reset-password {email} {password?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset password for a user (development only)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = $this->argument('email');
        $password = $this->argument('password') ?? 'password';

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("❌ Utilisateur introuvable : {$email}");
            return 1;
        }

        $user->update([
            'password' => Hash::make($password),
        ]);

        $this->info("✅ Mot de passe réinitialisé pour {$email}");
        $this->line("   Email    : {$email}");
        $this->line("   Password : {$password}");

        return 0;
    }
}
