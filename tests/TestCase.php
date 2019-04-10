<?php

namespace Tests;

use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function signIn(User $user = null) {
        $user = $user ?: create('App\User');
        $this->actingAs($user);
        return $this;
    }
}
