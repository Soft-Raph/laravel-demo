<?php

namespace App\Http\Controllers;

use App\Helpers\ApiKeyHelper;
use App\Helpers\ResponseHelper;
use App\Http\Requests\StoreApiKeyRequest;
use App\Http\Resources\ApiKeyResource;
use App\Models\ApiKey;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class ApiKeyController extends Controller
{

    /**
     * @var ApiKeyHelper
     */
    private $ApiKeyHelper;
    /**
     * @var Builder
     */
    private $ApiKey;

    public function __construct()
    {
        $this->middleware('auth');
        $this->ApiKey = ApiKey::query();
        $this->ApiKeyHelper = new ApiKeyHelper();
    }

    public function index(): \Illuminate\Http\JsonResponse
    {
        $api_keys = Cache::remember('cico-api-keys', 60 * 24, function (){
            return $this->ApiKey->get();
        });
        return ResponseHelper::success(ApiKeyResource::collection($api_keys), 'Api keys fetched successfully');
    }


    public function store(StoreApiKeyRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();

        $data['password'] = Hash::make($data['password']);
        $data['uuid'] = $this->ApiKeyHelper->generateUUID();
        $data['app_id'] = $this->ApiKeyHelper->generateAppID();
        $data['public_key'] = $this->ApiKeyHelper->generatePublicKey();
        $data['private_key'] = $this->ApiKeyHelper->generatePrivateKey();
        $data['user_id'] = auth()->user()->id;

        $create = $this->ApiKey->create($data);
        if (! $create){
            return ResponseHelper::error(500, 'Api key not created, contact support');
        }

        Cache::forget('cico-api-keys');
        return ResponseHelper::success(ApiKeyResource::make($create), 'Api key created successfully');
    }


    public function destroy($key): \Illuminate\Http\JsonResponse
    {
        $api_key = $this->ApiKey
            ->where('uuid', $key)
            ->first();
        if (! $api_key){
            return ResponseHelper::error(404, 'Api key not found');
        }

        $delete = $api_key->delete();
        if (! $delete){
            return ResponseHelper::error(500, 'Api key not deleted, contact support');
        }

        Cache::forget('cico-api-keys');
        return ResponseHelper::success(null, 'Api key deleted successfully');
    }
}
