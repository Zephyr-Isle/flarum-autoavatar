<?php

namespace Zephyrisle\AutoAvatar\AvatarDrivers;

use Flarum\User\User;
use Multiavatar;

class MultiavatarDriver
{
    /**
     * Multiavatar 原生输出 SVG。为了兼容 Flarum 头像上传链路，
     * 这里优先用 Imagick 转为 PNG。若服务器无 Imagick，则返回 null 由调用方降级。
     *
     * @return array{content: string, extension: string, mime: string}|null
     */
    public function generate(User $user): ?array
    {
        $seed = (string) ($user->email ?: $user->id);
        $multiavatar = new Multiavatar();
        $svgCode = $multiavatar($seed, null, null);

        if (!class_exists(\Imagick::class)) {
            return null;
        }

        $imagick = new \Imagick();
        $imagick->readImageBlob($svgCode);
        $imagick->setImageFormat('png32');
        $imagick->resizeImage(200, 200, \Imagick::FILTER_LANCZOS, 1, true);

        $content = (string) $imagick->getImageBlob();
        $imagick->clear();
        $imagick->destroy();

        if ($content === '') {
            return null;
        }

        return [
            'content' => $content,
            'extension' => 'png',
            'mime' => 'image/png',
        ];
    }
}
