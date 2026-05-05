<?php

namespace Zephyrisle\AutoAvatar\AvatarDrivers;

use Flarum\Settings\SettingsRepositoryInterface;
use Flarum\User\User;
use GuzzleHttp\Client;
use Throwable;

class ApiRandomAvatarDriver
{
    protected SettingsRepositoryInterface $settings;
    protected Client $client;

    public function __construct(SettingsRepositoryInterface $settings)
    {
        $this->settings = $settings;
        $this->client = new Client([
            'timeout' => 5.0,
            'allow_redirects' => true,
        ]);
    }

    /**
     * @return array{content: string, extension: string, mime: string}|null
     */
    public function generate(User $user): ?array
    {
        $apiUrl = $this->settings->get('zephyrisle-autoavatar.api_url', 'https://picsum.photos/200');
        $fallbackUrl = $this->settings->get('zephyrisle-autoavatar.api_fallback_url', '');

        $candidateUrls = array_filter([$apiUrl, $fallbackUrl], fn ($url) => is_string($url) && $url !== '');
        foreach ($candidateUrls as $url) {
            try {
                $response = $this->client->get($url);
                $content = (string) $response->getBody();

                if ($content === '') {
                    continue;
                }

                $mime = (string) $response->getHeaderLine('Content-Type');
                $mime = explode(';', $mime)[0] ?: 'image/png';

                return [
                    'content' => $content,
                    'extension' => $this->resolveExtensionByMime($mime),
                    'mime' => $mime,
                ];
            } catch (Throwable $e) {
                continue;
            }
        }

        return null;
    }

    private function resolveExtensionByMime(string $mime): string
    {
        return match ($mime) {
            'image/jpeg', 'image/jpg' => 'jpg',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
            default => 'png',
        };
    }
}
