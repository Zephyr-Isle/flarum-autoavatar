<?php

namespace Zephyrisle\AutoAvatar\Jobs;

use Flarum\Queue\AbstractJob;
use Flarum\Settings\SettingsRepositoryInterface;
use Flarum\User\User;
use Zephyrisle\AutoAvatar\AvatarDrivers\ApiRandomAvatarDriver;
use Zephyrisle\AutoAvatar\AvatarDrivers\LetterAvatarDriver;
use Zephyrisle\AutoAvatar\AvatarDrivers\MultiavatarDriver;

class GenerateAvatarJob extends AbstractJob
{
    protected int $userId;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    public function handle(
        SettingsRepositoryInterface $settings,
        LetterAvatarDriver $letterDriver,
        ApiRandomAvatarDriver $apiDriver,
        MultiavatarDriver $multiavatarDriver
    ): void {
        $user = User::find($this->userId);

        if (!$user || $user->avatar_url) {
            return;
        }

        $mode = $settings->get('zephyrisle-autoavatar.mode', 'letter');

        switch ($mode) {
            case 'api':
                $apiDriver->generate($user);
                break;
            case 'multiavatar':
                $multiavatarDriver->generate($user);
                break;
            case 'letter':
            default:
                $letterDriver->generate($user);
                break;
        }
    }
}
