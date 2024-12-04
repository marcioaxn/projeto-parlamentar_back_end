<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;

class DecryptEmails extends Command
{
    protected $signature = 'decrypt:emails';
    protected $description = 'Decrypt emails in the users table';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $users = User::all();

        foreach ($users as $user) {
            try {
                // Descriptografar o email
                $decryptedEmail = Crypt::decryptString($user->getOriginal('email'));

                // Atualizar o email para o valor descriptografado
                $user->update(['email' => $decryptedEmail]);
            } catch (Exception $e) {
                Log::error('Failed to decrypt email for user ' . $user->cod_user . ': ' . $e->getMessage());
            }
        }

        $this->info('Emails successfully decrypted!');
        return 0;
    }
}
