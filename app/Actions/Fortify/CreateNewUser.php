<?php

namespace App\Actions\Fortify;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     *
     * @throws ValidationException
     */
    public function create(array $input): User
    {
        // Auto-generate a username from name if not provided or empty.
        if (empty($input['username'])) {
            $input['username'] = $this->generateUsername($input['name'] ?? 'user');
        }

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => 'required',
            'username' => ['required', 'string', 'max:50', 'regex:/^[A-Za-z0-9_]+$/', Rule::unique(User::class)],
        ])->validate();

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'username' => $input['username'],
            'password' => Hash::make($input['password']),
        ]);

        $authorRole = Role::where('name', 'Author')->first();
        if ($authorRole) {
            $user->roles()->syncWithoutDetaching([$authorRole->id]);
        }

        return $user;
    }

    /**
     * Generate a unique, URL-friendly username from a display name.
     * Converts spaces to underscores (e.g. "Alaa Eid" → "Alaa_Eid").
     * Appends a numeric suffix if the base username is already taken.
     */
    protected function generateUsername(string $name): string
    {
        // Preserve case, replace spaces/dashes with underscores, strip non-alphanumeric/underscore chars.
        $base = preg_replace('/[^A-Za-z0-9_]/', '', str_replace([' ', '-'], '_', trim($name)));
        $base = $base ?: 'user';

        $username = $base;
        $counter = 1;

        while (User::where('username', $username)->exists()) {
            $username = $base.'_'.$counter;
            $counter++;
        }

        return $username;
    }
}
