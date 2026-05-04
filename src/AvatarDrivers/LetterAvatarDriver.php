<?php

namespace Zephyrisle\AutoAvatar\AvatarDrivers;

use Flarum\Settings\SettingsRepositoryInterface;
use Flarum\User\User;
use Zephyrisle\AutoAvatar\Helpers\AvatarSaver;

class LetterAvatarDriver
{
    protected SettingsRepositoryInterface $settings;
    protected AvatarSaver $saver;

    public function __construct(SettingsRepositoryInterface $settings, AvatarSaver $saver)
    {
        $this->settings = $settings;
        $this->saver = $saver;
    }

    public function generate(User $user): void
    {
        $username = $user->username;
        $letter = mb_strtoupper(mb_substr($username, 0, 1));

        $size = 200;
        $image = imagecreatetruecolor($size, $size);

        // Get settings
        $bgColorHex = $this->settings->get('zephyrisle-autoavatar.letter_bg_color', '#2196f3');
        $textColorHex = $this->settings->get('zephyrisle-autoavatar.letter_text_color', '#ffffff');
        $isCircle = (bool) $this->settings->get('zephyrisle-autoavatar.letter_shape_circle', true);

        // Convert Hex to RGB
        [$r, $g, $b] = sscanf($bgColorHex, "#%02x%02x%02x");
        $bgColor = imagecolorallocate($image, $r, $g, $b);

        [$tr, $tg, $tb] = sscanf($textColorHex, "#%02x%02x%02x");
        $textColor = imagecolorallocate($image, $tr, $tg, $tb);

        // Fill background
        if ($isCircle) {
            $transparent = imagecolorallocatealpha($image, 0, 0, 0, 127);
            imagefill($image, 0, 0, $transparent);
            imagesavealpha($image, true);
            imagefilledellipse($image, $size / 2, $size / 2, $size, $size, $bgColor);
        } else {
            imagefilledrectangle($image, 0, 0, $size, $size, $bgColor);
        }

        // Add text
        $fontSize = 5; // Built-in font size (1-5)
        $fontWidth = imagefontwidth($fontSize);
        $fontHeight = imagefontheight($fontSize);
        
        // Scale up the letter (crude but works without TTF)
        // We'll draw the letter to a smaller canvas and then resize it
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
        $content = ob_get_clean();
        imagedestroy($image);

        $this->saver->save($user, $content, 'png');
    }
}
