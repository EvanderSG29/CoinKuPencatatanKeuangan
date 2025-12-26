<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class SyncUsersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:sync {--force : Force sync without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize user data and ensure consistency';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting user synchronization...');

        if (!$this->option('force') && !$this->confirm('This will sync user data. Continue?')) {
            $this->info('User sync cancelled.');
            return;
        }

        // Get all users
        $users = User::all();
        $this->info("Found {$users->count()} users to process.");

        $bar = $this->output->createProgressBar($users->count());
        $bar->start();

        foreach ($users as $user) {
            // Set default profile image if not set
            if (!$user->path_foto) {
                $user->path_foto = 'users/profil.png';
                $user->save();
            }

            // Validate and clean phone numbers
            if ($user->no_telp && !preg_match('/^[0-9+\-\s()]+$/', $user->no_telp)) {
                $this->warn("Invalid phone number format for user {$user->username}: {$user->no_telp}");
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        $this->info('User synchronization completed successfully!');
        $this->info("Processed {$users->count()} users.");
    }
}
