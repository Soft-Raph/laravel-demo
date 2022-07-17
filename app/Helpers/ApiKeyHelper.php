<?php

namespace App\Helpers;

use App\Models\ApiKey;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class ApiKeyHelper
{
    /**
     * @var Builder
     */
    private $apikey;
    /**
     * @var int
     */
    private $key_length;

    public function __construct()
    {
        $this->apikey = ApiKey::query();
        $this->key_length = 20;
    }

    public function generateAppID(): string
    {
        $app_id = 'CICO-'.Str::random(($this->key_length/2));
        $check_if_exists = $this->apikey
            ->where('app_id', $app_id)
            ->exists();
        if ($check_if_exists){
            $this->generateAppID();
        }
        return $app_id;
    }

    public function generateUUID(): \Ramsey\Uuid\UuidInterface
    {
        $uuid = Str::uuid();
        $check_if_exists = $this->apikey
            ->where('uuid', $uuid)
            ->exists();
        if ($check_if_exists){
            $this->generateUUID();
        }
        return $uuid;
    }

    public function generatePublicKey(): string
    {
        $public_key = Str::random($this->key_length);
        $check_if_exists = $this->apikey
            ->where('public_key', $public_key)
            ->exists();
        if ($check_if_exists){
            $this->generatePublicKey();
        }
        return $public_key;
    }


    public function generatePrivateKey(): string
    {
        $private_key = Str::random($this->key_length);
        $check_if_exists = $this->apikey
            ->where('private_key', $private_key)
            ->exists();
        if ($check_if_exists){
            $this->generatePrivateKey();
        }
        return $private_key;
    }

}
