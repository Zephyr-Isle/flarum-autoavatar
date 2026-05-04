<?php

namespace Zephyrisle\AutoAvatar\AvatarDrivers;

use Flarum\Settings\SettingsRepositoryInterface;
use Flarum\User\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Zephyrisle\AutoAvatar\Helpers\AvatarSaver;

class ApiRandomAvatarDriver
{
    protected SettingsRepositoryInterface $settings;
    protected AvatarSaver $saver;
    protected Client $client;

    public function __construct(SettingsRepositoryInterface $settings, AvatarSaver $saver)
    {
        $this->settings = $settings;
        $this->saver = $saver;
        $this->client = new Client([
            'timeout' => 5.0,
            'verify' => false, // Some local setups might have SSL issues
        ]);
    }

    public function generate(User $user): void
    {
        $apiUrl = $this->settings->get('zephyrisle-autoavatar.api_url', 'https://picsum.photos/200');
        $fallbackUrl = $this->settings->get('zephyrisle-autoavatar.api_fallback_url');

        try {
            $response = $this->client->get($apiUrl);
            $content = $response->getBody()->getContents();
            $this->saver->save($user, $content);
        } catch (GuzzleException $e) {
            if ($fallbackUrl) {
                try {
                    $response = $this->client->get($fallbackUrl);
                    $content = $response->getBody()->getContents();
                    $this->saver->save($user, $content);
                } catch (GuzzleException $ex) {
                    // Fail silently or log
                }
            }
        }
    }
}
