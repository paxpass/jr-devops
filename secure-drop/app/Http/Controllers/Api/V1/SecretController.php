<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\SecretService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SecretController extends Controller
{
    public function __construct(
        private SecretService $secretService
    ) {}

    /**
     * Store a new secret
     * 
     * @group Secrets
     * @bodyParam content string required The secret content to encrypt. Example: my-secret-password
     * @bodyParam ttl integer optional Time to live in minutes. Example: 60
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'content' => 'required|string|max:10000',
            'ttl' => 'nullable|integer|min:1|max:10080',
        ]);

        $result = $this->secretService->createSecret(
            $validated['content'],
            $validated['ttl'] ?? null
        );

        return response()->json($result, 201);
    }

    /**
     * Retrieve and burn a secret
     * 
     * @group Secrets
     * @urlParam id string required The secret UUID. Example: 9c8f1234-5678-90ab-cdef-1234567890ab
     */
    public function show(string $id): JsonResponse
    {
        $content = $this->secretService->retrieveAndBurn($id);

        if ($content === null) {
            return response()->json([
                'error' => 'Secret not found or expired'
            ], 404);
        }

        return response()->json([
            'content' => $content,
            'message' => 'This secret has been permanently deleted'
        ]);
    }
}