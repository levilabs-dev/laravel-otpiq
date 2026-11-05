<?php

declare(strict_types=1);

namespace LeviLabs\LaravelOtpiq\Exceptions;

use Exception;
use Throwable;

class OtpiqException extends Exception
{
    /**
     * @param  array<string, mixed>|null  $responseData
     */
    public function __construct(
        string $message,
        public readonly ?int $statusCode = null,
        ?Throwable $previous = null,
        public readonly ?array $responseData = null,
    ) {
        parent::__construct($message, $statusCode ?? 0, $previous);
    }

    /**
     * @param  array<string, mixed>|null  $responseData
     */
    public static function requestFailed(string $action, ?int $statusCode, ?string $message, ?array $responseData = null): self
    {
        $text = $message !== null && $message !== ''
            ? $message
            : "OTPIQ {$action} request failed.";

        return new self($text, $statusCode, responseData: $responseData);
    }

    public function isAuthError(): bool
    {
        return $this->statusCode === 401;
    }

    public function isValidationError(): bool
    {
        return $this->statusCode === 400 && isset($this->responseData['error']);
    }

    public function isCreditError(): bool
    {
        if (isset($this->responseData['yourCredit'], $this->responseData['requiredCredit'])) {
            return true;
        }

        $message = strtolower($this->getMessage());

        return str_contains($message, 'insufficient credit') || str_contains($message, 'credit');
    }

    public function isRateLimitError(): bool
    {
        return $this->statusCode === 429;
    }

    public function isTrialModeError(): bool
    {
        return str_contains(strtolower($this->getMessage()), 'trial mode');
    }

    public function isSpendingThresholdError(): bool
    {
        return isset($this->responseData['spendingThreshold']);
    }

    public function isSenderIdError(): bool
    {
        return str_contains(strtolower($this->getMessage()), 'senderid');
    }

    public function getRemainingCredit(): ?float
    {
        $value = $this->responseData['yourCredit'] ?? $this->responseData['remainingCredit'] ?? null;

        return is_numeric($value) ? (float) $value : null;
    }

    public function getRequiredCredit(): ?float
    {
        $value = $this->responseData['requiredCredit'] ?? null;

        return is_numeric($value) ? (float) $value : null;
    }

    public function getRateLimitWaitMinutes(): ?int
    {
        $value = $this->responseData['waitMinutes'] ?? null;

        return is_numeric($value) ? (int) $value : null;
    }

    public function getFirstError(): ?string
    {
        $error = $this->responseData['error'] ?? null;

        return is_string($error) ? $error : null;
    }
}
