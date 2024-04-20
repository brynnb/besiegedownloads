<?php

namespace Acme\Services;

use Illuminate\Config\Repository;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;

class PasswordUpgrader
{
    protected $hasher;
    protected $config;

    public function __construct(HasherContract $hasher, Repository $config)
    {
        $this->hasher = $hasher;
        $this->config = $config;
    }

    public function checkLegacyPassword(AuthenticatableContract $user, $plain, $hashed)
    {
        if ($this->isLegacyPassword($plain, $hashed, $user->getSalt()))
        {
            $this->upgradeLegacyPassword($user, $plain);

            return true;
        }

        return false;
    }

    protected function upgradeLegacyPassword(AuthenticatableContract $user, $plain)
    {
        $password = $this->hasher->make($plain);

        $user->update([ 'password' => $password ]);
    }

    protected function isLegacyPassword($plain, $hashed, $salt)
    {
        $legacy = $this->performLegacyHash($plain, $salt);

        return $legacy === $hashed;
    }

    protected function performLegacyHash($plain, $salt)
    {
        $prefix = $this->config['auth.legacy.salt_prefix'];
//        $suffix = $this->config['auth.legacy.salt_suffix'];
        $method = $this->config['auth.legacy.hash_method'];
        $suffix = $salt;
        $plainHash = hash('sha512', $plain); //because legacy passwords were hashed by JS before passing


        return hash($method, $prefix . $plainHash . $suffix);
    }
}