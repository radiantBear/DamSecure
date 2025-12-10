<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $cas_login_url = 'https://login.oregonstate.edu/cas/login';
    protected $cas_validate_url = 'https://login.oregonstate.edu/cas/serviceValidate';
    protected $cas_service_url;


    public function __construct()
    {
        $this->cas_service_url = 'https://' . request()->getHost() . '/public/authenticate';
    }


    public function login()
    {
        (new ProjectController())->audit();

        if (app()->environment('local')) {
            return view('local_login');
        }

        return redirect("{$this->cas_login_url}?service={$this->cas_service_url}");
    }


    public function authenticate(Request $request)
    {
        if (app()->environment('local')) {
            $user_data = [
                'osuuid' => $request->input('osuuid'),
                'onid' => $request->input('onid'),
                'firstName' => $request->input('firstName') ?: $request->input('givenName'),
                'lastName' => $request->input('lastName') ?: $request->input('surname'),
                'email' => $request->input('email')
            ];
        } else {
            $ticket = $request->input('ticket');

            if (!$ticket) {
                return redirect('/')->withErrors('No CAS ticket provided');
            }

            $response = Http::withQueryParameters([
                'ticket' => $ticket,
                'service' => $this->cas_service_url
            ])->get($this->cas_validate_url);

            $user_data = $this->extractUser($response->body());
        }

        $user = User::firstOrCreate(
            ['osuuid' => $user_data['osuuid']],
            $user_data
        );

        Auth::login($user);

        $request->session()->regenerate();
        return redirect()->intended('projects');
    }


    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect("/");
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }


    protected function extractUser(string $xml)
    {
        $response = simplexml_load_string($xml)
            ->children('http://www.yale.edu/tp/cas')
            ->authenticationSuccess;
        $attributes = simplexml_load_string($xml)
            ->children('http://www.yale.edu/tp/cas')
            ->authenticationSuccess
            ->attributes;


        return [
            'osuuid' => $attributes->osuuid,
            'onid' => $response->user,
            'firstName' => $attributes->firstname ?: $attributes->givenName,
            'lastName' => $attributes->lastname ?: $attributes->surname,
            'email' => $attributes->email
        ];
    }
}
