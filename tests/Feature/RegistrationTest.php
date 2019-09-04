<?php

namespace Tests\Feature;

use App\User;
use App\Mail\PleaseConfirmYourEmail;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Mail;

class RegistrationTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_confirmation_email_is_sent_upon_registration() {
        Mail::fake();
        $this->post(route('register'), [
            'name' => 'JohnDoe',
            'email' => 'john@example.com',
            'password' => 'john1234',
            'password_confirmation' => 'john1234'
        ]);
        Mail::assertQueued(PleaseConfirmYourEmail::class);
    }

    /** @test */
    public function users_can_fully_confirm_their_email_address() {
        Mail::fake();
        
        $this->post(route('register'), [
            'name' => 'JohnDoe',
            'email' => 'john@example.com',
            'password' => 'john1234',
            'password_confirmation' => 'john1234'
        ]);

        $user = User::whereName('JohnDoe')->first();
        $this->assertFalse($user->confirmed);
        $this->assertNotNull($user->confirmation_token);

        $this->get(route('register.confirm', ['token' => $user->confirmation_token]))
            ->assertRedirect(route('threads'));

        tap($user->fresh(), function ($user) {
            $this->assertTrue($user->confirmed);
            $this->assertNull($user->confirmation_token);
        });
    }

    /** @test */
    public function confirming_an_invalid_token() {
        $this->get(route('register.confirm', ['token' => 'invalid']))
            ->assertRedirect(route('threads'))
            ->assertSessionHas('flash', 'Unknown token.');
    }
}