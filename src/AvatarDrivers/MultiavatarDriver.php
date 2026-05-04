<?php

namespace Zephyrisle\AutoAvatar\AvatarDrivers;

use Flarum\Settings\SettingsRepositoryInterface;
use Flarum\User\User;
use Multiavatar;
use Zephyrisle\AutoAvatar\Helpers\AvatarSaver;

class MultiavatarDriver
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
        // Use email or ID as seed
        $seed = $user->email ?: $user->id;
        
        $multiavatar = new Multiavatar();
        $svgCode = $multiavatar($seed, null, null);

        // Save as SVG
        $this->saver->save($user, $svgCode, 'svg');
    }
}
