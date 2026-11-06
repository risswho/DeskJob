<?php

namespace Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected static ?string $password = null;

    public function definition(): array
    {
        return [
            'nama' => fake('id_ID')->name(),
            'nip' => fake()->unique()->numerify('##########'),
            'username' => fake()->unique()->userName(),
            'password' => static::$password ??= Hash::make('password'),
            'jabatan' => fake('id_ID')->jobTitle(),
            'role' => 'staff',
            'status' => 'aktif',
            'remember_token' => Str::random(10),
        ];
    }

    public function viewer(): static
    {
        return $this->state(fn () => [
            'role' => 'viewer',
        ]);
    }

    public function staff(): static
    {
        return $this->state(fn () => [
            'role' => 'staff',
        ]);
    }

    public function superadmin(): static
    {
        return $this->state(fn () => [
            'role' => 'superadmin',
        ]);
    }

    public function nonaktif(): static
    {
        return $this->state(fn () => [
            'status' => 'nonaktif',
        ]);
    }
}