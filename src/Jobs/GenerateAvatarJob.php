<?php

namespace Zephyrisle\AutoAvatar\Jobs;

use Flarum\Queue\AbstractJob;
use Flarum\Settings\SettingsRepositoryInterface;
use Flarum\User\User;
use Throwable;
use Zephyrisle\AutoAvatar\AvatarDrivers\ApiRandomAvatarDriver;
use Zephyrisle\AutoAvatar\AvatarDrivers\LetterAvatarDriver;
use Zephyrisle\AutoAvatar\AvatarDrivers\MultiavatarDriver;
use Zephyrisle\AutoAvatar\Helpers\AvatarSaver;

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
        MultiavatarDriver $multiavatarDriver,
        AvatarSaver $saver
    ): void {
        $user = User::query()->find($this->userId);

        if (!$user || $user->original_avatar_url) {
            return;
        }

        $mode = $settings->get('zephyrisle-autoavatar.mode', 'letter');

        $payload = null;

        try {
            $payload = match ($mode) {
                'api' => $apiDriver->generate($user),
                'multiavatar' => $multiavatarDriver->generate($user),
                default => $letterDriver->generate($user),
            };
        } catch (Throwable $e) {
            $payload = null;
        }

        // 任何模式失败都回退到字母头像，确保任务最终可完成。
        if ($payload === null) {
            $payload = $letterDriver->generate($user);
        }

        if ($payload === null) {
            return;
        }

        $saver->save($user, $payload['content'], $payload['extension'], $payload['mime']);
    }
}
