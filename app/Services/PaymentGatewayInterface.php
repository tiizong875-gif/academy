<?php
namespace App\Services;

interface PaymentGatewayInterface {
    /**
     * Get unique provider identifier code (e.g. 'manual_khqr', 'bakong', 'payway')
     */
    public function getIdentifier(): string;

    /**
     * Check if the gateway is officially configured and enabled with credentials
     */
    public function isAvailable(): bool;

    /**
     * Check transaction status against provider's official API
     * Returns an array with ['verified' => bool, 'transaction_id' => ?string, 'amount' => ?float, 'currency' => ?string, 'message' => string]
     */
    public function checkTransaction(string $orderNumber, ?string $transactionRef = null): array;

    /**
     * Verify incoming webhook notification signature and payload
     */
    public function verifyWebhook(array $payload, array $headers): array;
}
