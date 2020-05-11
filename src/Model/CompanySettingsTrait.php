<?php

namespace CommunityDS\Deputy\Api\Model;

trait CompanySettingsTrait
{
    
    /**
     * Get All settings for this Company
     *
     * @return array
     */
    public function getSettings()
    {
        try {
            $settings = $this->getSuperviseCompanySettings();
        } catch (\Exception $e) {
            return [];
        }
        
        return is_array($settings) ? $settings : [];
    }
    
    /**
     * Set All settings for given key/value pairs
     *
     * @param mixed[] $settingsKeyValues Key => Value pairs
     *
     * @return boolean
     *
     * @throws \Exception When issue
     */
    public function setSettings($settingsKeyValues)
    {
        $settingsPayLoad = $settingsKeyValues;
        return $this->postSuperviseCompanySettings($settingsPayLoad);
    }
    
    /**
     * Get single setting for this Company
     *
     * @param string $settingKey
     *
     * @return mixed|null
     */
    public function getSetting($settingKey)
    {
        $allSettings = $this->getSettings();
        if (!key_exists($settingKey, $allSettings)) {
            return null;
        }
        return $allSettings[$settingKey];
    }
    
    /**
     * Set a single setting for this Company
     *
     * @param string $settingKey
     * @param mixed $settingValue
     *
     * @return boolean
     *
     * @throws \Exception When issue
     */
    public function setSetting($settingKey, $settingValue)
    {
        $settingsPayLoad = ["{$settingKey}" => $settingValue];
        return $this->postSuperviseCompanySettings($settingsPayLoad);
    }
    
    /**
     * Sends a payload to the POST /supervise/company/{intCompanyId}/settings endpoint
     *
     * @param array $payload Payload of settings changes
     *
     * @return boolean
     *
     * @throws \Exception When primaryKey not provided
     */
    protected function postSuperviseCompanySettings($payload)
    {
        $companyId = $this->getPrimaryKey();
        if (empty($companyId)) {
            throw new \Exception('postSuperviseCompanySettings does not support updating Company without primaryKey');
        }

        $response = $this->getWrapper()->client->post(
            "supervise/company/{$companyId}/settings",
            $payload
        );
        if ($response === false) {
            $this->setErrorsFromResponse($this->getWrapper()->client->getLastError());
            return false;
        }
        return true;
    }
    
    /**
     * Sends a request to the GET /supervise/company/{intCompanyId}/settings endpoint
     *
     * @return boolean
     *
     * @throws \Exception When primaryKey not provided
     */
    protected function getSuperviseCompanySettings()
    {
        $companyId = $this->getPrimaryKey();
        if (empty($companyId)) {
            throw new \Exception('getSuperviseCompanySettings cannot support getting settings for Company without primaryKey');
        }
        
        $response = $this->getWrapper()->client->get(
            "supervise/company/{$companyId}/settings"
        );
        if ($response === false) {
            $this->setErrorsFromResponse($this->getWrapper()->client->getLastError());
            return false;
        }
        return $response;
    }
}
