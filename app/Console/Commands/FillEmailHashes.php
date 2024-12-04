<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

class FillEmailHashes extends Command
{
    protected $signature = 'fill:email-hashes';
    protected $description = 'Fill email_hash for existing users';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $users = User::whereNull('email_hash')->get();

        foreach ($users as $user) {
            try {
                $email = $user->email;
                $user->email_hash = Hash::make($email);
                $user->save();
            } catch (DecryptException $e) {
                $this->error("Failed to decrypt email for user ID {$user->id}");
            }
        }

        $this->info('Email hashes filled successfully.');
    }
}
