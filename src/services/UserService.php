<?php


namespace reunionou\services;

use reunionou\models\User;

class UserService {

    public static function getUserById(int $id): ?array
    {
        $user = User::select('id', 'firstname', 'lastname','email', 'created_at')
                    ->where('id', '=', $id)
                    ->first();
    
        return $user ? $user->toArray() : null;
    }

    public static function registerUser(string $firstname, string $lastname, string $email, string $password): ?array
    {
        $existingUser = User::where('email', '=', $email)->first();
        if ($existingUser !== null)
            return null;

        $user = new User();
        $user->firstname = $firstname;
        $user->lastname = $lastname;
        $user->email = $email;
        $user->password = password_hash($password, PASSWORD_DEFAULT);
        $user->save();

        return $user->toArray();
    }

    public static function loginUser(string $email, string $password): ?array
        {
            $user = User::where('email', '=', $email)->first();

            if ($user === null) {
                return null;
            }

            $isPasswordCorrect = password_verify($password, $user->password);

            if ($isPasswordCorrect) {
                return $user->toArray();
            } else {
                return null;
            }
        }
}