<?php


namespace Acme\Services;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use Illuminate\Auth\EloquentUserProvider as LaravelUserProvider;

class UserProvider extends LaravelUserProvider implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    protected $upgrader;

    public function __construct(HasherContract $hasher, PasswordUpgrader $upgrader, $model)
    {
        $this->hasher = $hasher;
        $this->model = $model;
        $this->upgrader = $upgrader;
    }

    public function validateCredentials(AuthenticatableContract $user, array $credentials)
    {
        // Get the plain password from the user input and
        // get the hashed password from the user model.
        $plain = $credentials['password'];
        $hashed = $user->getAuthPassword();

        // Step 1.
        if ($this->hasher->check($plain, $hashed))
        {
            // Step 2.
            return true;
        }
        else
        {
            // Step 3.
            return $this->upgrader->checkLegacyPassword($user, $plain, $hashed);
        }
    }
}