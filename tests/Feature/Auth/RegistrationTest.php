<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

use function Pest\Laravel\{assertDatabaseHas, postJson};
use function PHPUnit\Framework\assertTrue;

test('should be able to register in the application', function () {

    postJson(route('register'), [
        'name'     => 'John Doe',
        'email'    => 'joe@doe.com',
        'password' => 'password',
    ])->assertSessionHasNoErrors();

    assertDatabaseHas('users', [
        'name'  => 'John Doe',
        'email' => 'joe@doe.com',
    ]);

    $joeDoe = User::whereEmail('joe@doe.com')->first();

    assertTrue(Hash::check('password', $joeDoe->password));
});

describe('validations', function () {

    test('name', function ($rule, $value, $meta = []) {
        //dd($rule, $value);
        postJson(route('register'), [
            'name' => $value,
        ])->assertJsonValidationErrors([
            'name' => __(
                'validation.' . $rule,
                array_merge(['attribute' => 'name'], $meta)
            ),
        ]);

    })->with([
        'required' => ['required', ''],
        'min:3'    => ['min', 'AB', ['min' => 3]],
        'max:255'  => ['max', str_repeat('*', 256), ['max' => 255]],
    ])->only();
});
