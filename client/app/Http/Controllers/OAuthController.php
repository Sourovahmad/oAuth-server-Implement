<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class OAuthController extends Controller
{
   
    public function redirect(){


            $queries = http_build_query([

            'client_id' => '2',
            'redirect_uri' => 'http://127.0.0.1:8000/oauth/callback',
            'response_type' => 'code'
           
              ]);

       return redirect('http://127.0.0.1:7000/oauth/authorize?' . $queries);

    }


       public function callback(Request $request)
    {


        $response = Http::post('http://127.0.0.1:7000/oauth/token',[
            'grant_type' => 'authorization_code',
            'client_id' => '2',
            'client_secret' =>'UabsSaKTryn6DFYzjHJwV8kjcgAipYEQwz8uxws8',
            'redirect_uri' => 'http://127.0.0.1:8000/oauth/callback',
            'code' => $request->code



        ]);
         
          
                $response = $response->json();
                
                $request->user()->token()->delete();

                $request->user()->token()->create([
                    'access_token' => $response['access_token'],
                ]);
                return redirect('/home')->with('success', 'You Are Authorized');

    }


    // public function redirect()
    // {
    //     $queries = http_build_query([
    //         'client_id' => config('services.oauth_server.client_id'),
    //         'redirect_uri' => config('services.oauth_server.redirect'),
    //         'response_type' => 'code',
    //         'scope' => 'view-posts'
    //     ]);

    //     return redirect(config('services.oauth_server.uri') . '/oauth/authorize?' . $queries);
    // }

    // public function callback(Request $request)
    // {
    //     $response = Http::post(config('services.oauth_server.uri') . '/oauth/token', [
    //         'grant_type' => 'authorization_code',
    //         'client_id' => config('services.oauth_server.client_id'),
    //         'client_secret' => config('services.oauth_server.client_secret'),
    //         'redirect_uri' => config('services.oauth_server.redirect'),
    //         'code' => $request->code
    //     ]);

    //     $response = $response->json();

    //     $request->user()->token()->delete();

    //     $request->user()->token()->create([
    //         'access_token' => $response['access_token'],
    //         'expires_in' => $response['expires_in'],
    //         'refresh_token' => $response['refresh_token']
    //     ]);

    //     return redirect('/home');
    // }

    // public function refresh(Request $request)
    // {
    //     $response = Http::post(config('services.oauth_server.uri') . '/oauth/token', [
    //         'grant_type' => 'refresh_token',
    //         'refresh_token' => $request->user()->token->refresh_token,
    //         'client_id' => config('services.oauth_server.client_id'),
    //         'client_secret' => config('services.oauth_server.client_secret'),
    //         'redirect_uri' => config('services.oauth_server.redirect'),
    //         'scope' => 'view-posts'
    //     ]);

    //     if ($response->status() !== 200) {
    //         $request->user()->token()->delete();

    //         return redirect('/home')
    //             ->withStatus('Authorization failed from OAuth server.');
    //     }

    //     $response = $response->json();
    //     $request->user()->token()->update([
    //         'access_token' => $response['access_token'],
    //         'expires_in' => $response['expires_in'],
    //         'refresh_token' => $response['refresh_token']
    //     ]);

    //     return redirect('/home');
    // }
}