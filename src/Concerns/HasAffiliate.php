<?php namespace Phuclh\Affast\Concerns;

trait HasAffiliate
{
    /**
     * Save referral id.
     *
     * @param $referral
     * @return self
     */
    public function saveReferral($referral): self
    {
        $referralId = is_array($referral) ? ($referral['data']['id'] ?? null) : $referral;

        $this->forceFill(['referral' => $referralId])->save();

        return $this->refresh();
    }

    /**
     * Check if the current model has referral or not.
     *
     * @return bool
     */
    public function hasReferral(): bool
    {
        return !empty($this->referral);
    }

    /**
     * Get the referral.
     *
     * @return ?string
     */
    public function getReferral(): ?string
    {
        return $this->referral;
    }

    /**
     * Return Affast metadata.
     *
     * @param array $options
     * @return array
     */
    public function affastMetadata(array $options = []): array
    {
        return array_merge([
            'affast_referral' => $this->referral
        ], $options);
    }
}
