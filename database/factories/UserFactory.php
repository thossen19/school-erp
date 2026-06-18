<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\School;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'username' => fake()->unique()->userName(),
            'phone' => fake()->phoneNumber(),
            'status' => true,
            'locale' => 'en',
            'theme_preference' => 'light',
            'school_id' => School::factory(),
            'branch_id' => Branch::factory(),
            'user_type' => 'staff',
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function superAdmin(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Super Admin',
            'email' => 'admin@aischool.com',
            'username' => 'superadmin',
            'user_type' => 'super_admin',
            'school_id' => null,
            'branch_id' => null,
        ]);
    }

    public function schoolAdmin(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'School Admin',
            'email' => 'school@aischool.com',
            'username' => 'schooladmin',
            'user_type' => 'school_admin',
        ]);
    }
}
