<?php

namespace Phuclh\Affast;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class Affast
{
    /** @var string */
    protected $apiUrl;

    /** @var \Illuminate\Http\Client\PendingRequest */
    public $http;

    public function __construct(string $apiUrl, string $apiToken)
    {
        $this->apiUrl = $apiUrl;

        $this->http = Http::withToken($apiToken)->withHeaders([
            'Accept' => 'application/json',
        ]);
    }

    /**
     * @param string $affiliateTag
     * @param array $referee
     * @param bool $isRecurring
     * @return array|mixed
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function createReferral(string $affiliateTag, array $referee, bool $isRecurring = false)
    {
        $response = $this->http->post($this->getApiUrl() . 'referrals', [
            'affiliate_tag' => $affiliateTag,
            'referee_id' => $referee['id'],
            'referee_name' => $referee['name'] ?? null,
            'referee_email' => $referee['email'] ?? null,
            'referee_commission_amount' => $referee['commission_amount'] ?? null,
            'is_recurring' => $isRecurring,
        ]);

        return $response->throw()->json();
    }

    /**
     * @param string $id
     * @return array|mixed
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function findReferral(string $id)
    {
        $response = $this->http->get($this->getApiUrl() . 'referrals/' . $id);

        return $response->throw()->json();
    }

    /**
     * @param string $referralId
     * @param float $amount
     * @param string|null $createdReason
     * @return array|mixed
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function createCommission(string $referralId, float $invoiceAmount, ?string $createdReason = null)
    {
        $response = $this->http->post($this->getApiUrl() . 'commissions', [
            'referral_id' => $referralId,
            'invoice_amount' => $invoiceAmount,
            'created_reason' => $createdReason,
        ]);

        return $response->throw()->json();
    }

    /**
     * @param string $commissionId
     * @param string $reason
     * @return array|mixed
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function cancelCommission(string $commissionId, string $reason)
    {
        $response = $this->http->post($this->getApiUrl() . 'commissions/' . $commissionId . '/cancel', [
            'reason' => $reason,
        ]);

        return $response->throw()->json();
    }

    /**
     * @param string $commissionId
     * @param array $attributes
     * @return array|mixed
     * @throws \Illuminate\Http\Client\RequestException
     */
    //public function updateCommission(string $commissionId, array $attributes)
    //{
    //    $response = $this->http->put($this->getApiUrl() . 'commissions/' . $commissionId, $attributes);
    //
    //    return $response->throw()->json();
    //}

    /**
     * @return bool
     */
    public function hasAffiliateTag(): bool
    {
        return isset($_COOKIE[$this->affiliateCookieKey()]) && ! empty($_COOKIE[$this->affiliateCookieKey()]);
    }

    /**
     * @return string|null
     */
    public function getAffiliateTag(): ?string
    {
        return $_COOKIE[$this->affiliateCookieKey()] ?? null;
    }

    /**
     * Remove Affast affiliate cookie.
     *
     * @return void
     */
    public function removeCookie()
    {
        unset($_COOKIE[$this->affiliateCookieKey()]);

        setcookie($this->affiliateCookieKey(), null, -1, '/');
    }

    private function affiliateCookieKey(): string
    {
        return 'affast_affiliate';
    }

    protected function getApiUrl(): string
    {
        return Str::finish($this->apiUrl, '/');
    }
}
