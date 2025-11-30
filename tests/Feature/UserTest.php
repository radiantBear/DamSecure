<?php

namespace Tests\Feature;

use App\Models;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;


    public function test_local_login_loads_form(): void
    {
        app()->detectEnvironment(fn () => 'local');

        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertViewIs('local_login');
    }


    public function test_production_login_redirects_to_cas(): void
    {
        app()->detectEnvironment(fn () => 'production');

        $response = $this->get('/login');

        $response->assertRedirectContains('https://login.oregonstate.edu');

        // Make sure the redirect-back URL is properly built
        $response->assertRedirectContains('https://127.0.0.1/public/authenticate');
    }

    public function test_local_form_authentication_creates_user(): void
    {
        app()->detectEnvironment(fn () => 'local');

        $response = $this->get(
            '/authenticate?osuuid=12345678901&onid=beaverb&firstName=Benny&lastName=Beaver&email=beaverb@oregonstate.edu'
        );

        $response->assertRedirect('/projects');
        $this->assertAuthenticatedAs(Models\User::firstWhere('osuuid', 12345678901));
        $this->assertDatabaseHas('users', [
            'osuuid' => 12345678901,
            'onid' => 'beaverb',
            'firstName' => 'Benny',
            'lastName' => 'Beaver',
            'email' => 'beaverb@oregonstate.edu'
        ]);
    }

    public function test_production_cas_authentication_creates_user(): void
    {
        $cas_response = <<<XML
<?xml version="1.0"?>
<cas:serviceResponse xmlns:cas="http://www.yale.edu/tp/cas">
    <cas:authenticationSuccess>
        <cas:user>beaverb</cas:user>
        <cas:attributes>
            <cas:eduPersonPrimaryAffiliation>student</cas:eduPersonPrimaryAffiliation>
            <cas:email>beaverb@oregonstate.edu</cas:email>
            <cas:givenName>Benny</cas:givenName>
            <cas:lastname>Beaver</cas:lastname>
            <cas:firstname>Benny</cas:firstname>
            <cas:fullname>Beaver, Benny</cas:fullname>
            <cas:osuprimarymail>beaverb@oregonstate.edu</cas:osuprimarymail>
            <cas:osuuid>12345678901</cas:osuuid>
            <cas:surname>Beaver</cas:surname>
            <cas:commonName>Beaver, Benny</cas:commonName>
            <cas:eduPersonPrincipalName>beaverb@oregonstate.edu</cas:eduPersonPrincipalName>
            <cas:uid>beaverb</cas:uid>
        </cas:attributes>
    </cas:authenticationSuccess>
</cas:serviceResponse>
XML;
        app()->detectEnvironment(fn () => 'production');
        Http::fake([
            'https://login.oregonstate.edu/cas/serviceValidate*' => Http::response($cas_response)
        ]);

        $response = $this->get('/authenticate?ticket=fakeTicket');

        $response->assertRedirect('/projects');
        $this->assertAuthenticatedAs(Models\User::firstWhere('osuuid', 12345678901));
        $this->assertDatabaseHas('users', [
            'osuuid' => 12345678901,
            'onid' => 'beaverb',
            'firstName' => 'Benny',
            'lastName' => 'Beaver',
            'email' => 'beaverb@oregonstate.edu'
        ]);
    }

    public function test_local_form_authentication_finds_user(): void
    {
        app()->detectEnvironment(fn () => 'local');
        $user = Models\User::factory([
            'osuuid' => 12345678901,
            'onid' => 'beaverb',
            'firstName' => 'Benny',
            'lastName' => 'Beaver',
            'email' => 'beaverb@oregonstate.edu'
        ])->create();

        $response = $this->get(
            '/authenticate?osuuid=12345678901&onid=beaverb&firstName=Benny&lastName=Beaver&email=beaverb@oregonstate.edu'
        );

        $response->assertRedirect('/projects');
        $this->assertAuthenticatedAs(Models\User::find($user->id));
        $this->assertDatabaseCount('users', 1);
    }

    public function test_production_cas_authentication_finds_user(): void
    {
        $cas_response = <<<XML
<?xml version="1.0"?>
<cas:serviceResponse xmlns:cas="http://www.yale.edu/tp/cas">
    <cas:authenticationSuccess>
        <cas:user>beaverb</cas:user>
        <cas:attributes>
            <cas:eduPersonPrimaryAffiliation>student</cas:eduPersonPrimaryAffiliation>
            <cas:email>beaverb@oregonstate.edu</cas:email>
            <cas:givenName>Benny</cas:givenName>
            <cas:lastname>Beaver</cas:lastname>
            <cas:firstname>Benny</cas:firstname>
            <cas:fullname>Beaver, Benny</cas:fullname>
            <cas:osuprimarymail>beaverb@oregonstate.edu</cas:osuprimarymail>
            <cas:osuuid>12345678901</cas:osuuid>
            <cas:surname>Beaver</cas:surname>
            <cas:commonName>Beaver, Benny</cas:commonName>
            <cas:eduPersonPrincipalName>beaverb@oregonstate.edu</cas:eduPersonPrincipalName>
            <cas:uid>beaverb</cas:uid>
        </cas:attributes>
    </cas:authenticationSuccess>
</cas:serviceResponse>
XML;
        app()->detectEnvironment(fn () => 'production');
        Http::fake([
            'https://login.oregonstate.edu/cas/serviceValidate*' => Http::response($cas_response)
        ]);
        $user = Models\User::factory([
            'osuuid' => 12345678901,
            'onid' => 'beaverb',
            'firstName' => 'Benny',
            'lastName' => 'Beaver',
            'email' => 'beaverb@oregonstate.edu'
        ])->create();

        $response = $this->get('/authenticate?ticket=fakeTicket');

        $response->assertRedirect('/projects');
        $this->assertAuthenticatedAs(Models\User::find($user->id));
        $this->assertDatabaseCount('users', 1);
    }
}
