<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;

class EncryptEmails extends Command
{
    protected $signature = 'encrypt:emails';
    protected $description = 'Encrypt emails in the users table if not already encrypted';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $users = User::all();

        foreach ($users as $user) {
            try {
                $decrypted = $user->email;
                $key = base64_decode(env('ENCRYPTION_KEY'));
                $data = base64_decode($decrypted);
                $iv = substr($data, 0, 16);
                $encrypted = substr($data, 16);

                // Tenta descriptografar o e-mail para verificar se já está criptografado
                $test = openssl_decrypt($encrypted, 'AES-256-CBC', $key, 0, $iv);

                if ($test === false) {
                    // Se falhar, significa que o e-mail não está criptografado
                    $user->email = $user->email; // O mutator irá criptografar o email
                    $user->save();
                }
            } catch (Exception $e) {
                Log::error('Failed to encrypt email for user ' . $user->id . ': ' . $e->getMessage());
            }
        }

        $this->info('Emails successfully encrypted!');
        return 0;
    }
}
