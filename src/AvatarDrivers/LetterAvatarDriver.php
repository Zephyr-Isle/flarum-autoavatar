<?php

namespace Zephyrisle\AutoAvatar\AvatarDrivers;

use Flarum\Settings\SettingsRepositoryInterface;
use Flarum\User\User;

class LetterAvatarDriver
{
    protected SettingsRepositoryInterface $settings;

    public function __construct(SettingsRepositoryInterface $settings)
    {
        $this->settings = $settings;
    }

    /**
     * @return array{content: string, extension: string, mime: string}|null
     */
    public function generate(User $user): ?array
    {
        if (!function_exists('imagecreatetruecolor')) {
            return null;
        }

        $letter = mb_strtoupper(mb_substr((string) $user->username, 0, 1));
        if ($letter === '') {
            $letter = '?';
        }

        $size = 200;
        $image = imagecreatetruecolor($size, $size);
        if ($image === false) {
            return null;
        }

        $bgColorHex = $this->settings->get('zephyrisle-autoavatar.letter_bg_color', '#2196f3');
        $textColorHex = $this->settings->get('zephyrisle-autoavatar.letter_text_color', '#ffffff');
        $isCircle = filter_var($this->settings->get('zephyrisle-autoavatar.letter_shape_circle', '1'), FILTER_VALIDATE_BOOL);

        [$r, $g, $b] = $this->parseHexColor($bgColorHex, [33, 150, 243]);
        $bgColor = imagecolorallocate($image, $r, $g, $b);

        [$tr, $tg, $tb] = $this->parseHexColor($textColorHex, [255, 255, 255]);
        $textColor = imagecolorallocate($image, $tr, $tg, $tb);

        if ($isCircle) {
            $transparent = imagecolorallocatealpha($image, 0, 0, 0, 127);
            imagefill($image, 0, 0, $transparent);
            imagesavealpha($image, true);
            imagefilledellipse($image, $size / 2, $size / 2, $size, $size, $bgColor);
        } else {
            imagefilledrectangle($image, 0, 0, $size, $size, $bgColor);
        }

        $fontSize = 5;
        $fontWidth = imagefontwidth($fontSize);
        $fontHeight = imagefontheight($fontSize);

        $tempLetter = imagecreatetruecolor($fontWidth, $fontHeight);
        imagefilledrectangle($tempLetter, 0, 0, $fontWidth, $fontHeight, $bgColor);
        imagestring($tempLetter, $fontSize, 0, 0, $letter, $textColor);

        $targetW = $size * 0.6;
        $targetH = ($targetW / $fontWidth) * $fontHeight;

        imagecopyresampled(
            $image, $tempLetter,
            ($size - $targetW) / 2, ($size - $targetH) / 2,
            0, 0,
            (int)$targetW, (int)$targetH,
            $fontWidth, $fontHeight
        );
        imagedestroy($tempLetter);

        ob_start();
        imagepng($image);
        $content = ob_get_clean() ?: '';
        imagedestroy($image);

        return [
            'content' => $content,
            'extension' => 'png',
            'mime' => 'image/png',
        ];
    }

    /**
     * @return array{0:int,1:int,2:int}
     */
    private function parseHexColor(string $hex, array $fallback): array
    {
        if (!preg_match('/^#?[0-9a-fA-F]{6}$/', $hex)) {
            return $fallback;
        }

        $normalized = ltrim($hex, '#');

        return [
            hexdec(substr($normalized, 0, 2)),
            hexdec(substr($normalized, 2, 2)),
            hexdec(substr($normalized, 4, 2)),
        ];
    }
}
