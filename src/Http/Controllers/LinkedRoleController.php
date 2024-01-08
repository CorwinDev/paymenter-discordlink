<?php

namespace CorwinDev\PaymenterDiscordLink\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;

class LinkedRoleController extends Controller
{
    public function index()
    {
        config()->set('services.discord.redirect', route('linkedroles.callback'));
        $url = 'https://discord.com/api/oauth2/authorize?client_id=' . config('services.discord.client_id') . '&redirect_uri=' . urlencode(config('services.discord.redirect')) . '&response_type=code&scope=role_connections.write%20identify';

        return redirect($url);
    }

    public function callback(Request $request)
    {
        config()->set('services.discord.redirect', route('linkedroles.callback'));
        $code = $request->input('code');
        if (!$code)
            return redirect()->route('index')->with('error', 'Something went wrong while linking your discord account');
        $token = $this->getAccessToken($code);
        if (!$token)
            return redirect()->route('index')->with('error', 'Something went wrong while linking your discord account');
        $user = $this->getUser($token);
        if (!$user)
            return redirect()->route('index')->with('error', 'Something went wrong while linking your discord account');
        $this->updateMetaData($user['id'], $token);
        return redirect()->route('index')->with('success', 'Your discord account has been linked!');
    }

    private function getAccessToken($code)
    {
        $url = 'https://discord.com/api/oauth2/token';
        $response = Http::asForm()->post($url, [
            'client_id' => config('services.discord.client_id'),
            'client_secret' => config('services.discord.client_secret'),
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => config('services.discord.redirect'),
            'scope' => 'role_connections.write'
        ]);

        if ($response->failed())
            return false;
        return $response->json()['access_token'];
    }

    private function getUser($token)
    {
        $url = 'https://discord.com/api/v8/users/@me';
        $response = Http::withToken($token)->get($url);
        if ($response->failed())
            return false;
        return $response->json();
    }

    private function updateMetaData($id, $token)
    {
        $url = 'https://discord.com/api/v10/users/@me/applications/' . config('services.discord.client_id') . '/role-connection';
        $user = User::find(auth()->user()->id);
        $products = $user->orderProducts()->where('status', 'paid')->get();
        $activeProducts = count($products);
        $response = Http::withToken($token)->put($url, [
            'platform_name' => 'Paymenter',
            'metadata' => [
                'syncedwithpaymenter' => true,
                'activeproducts' => $activeProducts
            ]
        ]);
    }
}
