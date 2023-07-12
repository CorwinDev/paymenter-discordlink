<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;

class DiscordLink extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'discord:link';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pushes linked roles to discord';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (!config('services.discord.client_id'))
            return $this->error('You need to provide a discord client id in the paymenter panel');

        $botToken = $this->ask('What is the bot token?');
        if (!$botToken)
            return $this->error('You need to provide a bot token');

        $syncedWithPaymenter = $this->ask('What should the name of the synced with paymenter be?', 'Is registered on the website');

        $activeProducts = $this->ask('What should the name of the active products be?', 'Active Products');

        $url = 'https://discord.com/api/v10/applications/' . config('services.discord.client_id') . '/role-connections/metadata';

        $response = Http::withHeaders([
            'Authorization' => 'Bot ' . $botToken,
        ])->put($url, [
            [
                'key' => 'syncedwithpaymenter',
                'name' => $syncedWithPaymenter,
                'description' => 'Is logged in on the website',
                'type' => 7,
            ],
            [
                'key' => 'activeproducts',
                'name' => $activeProducts,
                'description' => 'How many active products the user has',
                'type' => 2,
            ]
        ]);

        if ($response->failed()) {
            dd($response->json());
            return $this->error('Something went wrong while pushing the linked roles to discord');
        }
        $this->info('Linked roles have been pushed to discord');
        return 0;
    }
}
