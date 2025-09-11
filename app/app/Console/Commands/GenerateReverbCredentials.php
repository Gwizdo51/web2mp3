<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;


class GenerateReverbCredentials extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reverb:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display a new set of credentials';

    /**
     * Handles the random generation of the credentials
     * @return string[]
     */
    protected static function generate_reverb_credentials(): array {
        $digits = '0123456789';
        $n_digits = strlen($digits);
        $all = "{$digits}abcdefghijklmnopqrstuvwxyz";
        $n_all = strlen($all);
        $id = '';
        // the ID should have 6 random digits
        for ($i = 0; $i < 6; $i++) {
            $id .= $digits[random_int(0, $n_digits - 1)];
        }
        $key = '';
        $secret = '';
        // both the key and the secret should have 20 random characters
        for ($i = 0; $i < 20; $i++) {
            $key .= $all[random_int(0, $n_all - 1)];
            $secret .= $all[random_int(0, $n_all - 1)];
        }
        return [
            'id' => $id,
            'key' => $key,
            'secret' => $secret,
        ];
    }

    /**
     * Execute the console command.
     */
    public function handle() {
        ['id' => $id, 'key' => $key, 'secret' => $secret] = self::generate_reverb_credentials();
        $this->info('ID :');
        $this->warn("    {$id}");
        $this->info('KEY :');
        $this->warn("    {$key}");
        $this->info('SECRET :');
        $this->warn("    {$secret}");
    }
}
