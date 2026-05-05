<?php

namespace Zephyrisle\AutoAvatar\Helpers;

use Flarum\User\AvatarUploader;
use Flarum\User\User;
use RuntimeException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AvatarSaver
{
    protected AvatarUploader $uploader;

    public function __construct(AvatarUploader $uploader)
    {
        $this->uploader = $uploader;
    }

    public function save(User $user, string $content, string $extension = 'png', string $mimeType = 'image/png'): void
    {
        if ($content === '') {
            throw new RuntimeException('Avatar content is empty.');
        }

        $tempFile = tempnam(sys_get_temp_dir(), 'avatar');
        if ($tempFile === false) {
            throw new RuntimeException('Unable to create temporary avatar file.');
        }

        file_put_contents($tempFile, $content);

        $uploadedFile = new UploadedFile(
            $tempFile,
            'avatar.'.$extension,
            $mimeType,
            null,
            true
        );

        try {
            $this->uploader->upload($user, $uploadedFile);
            $user->save();
        } finally {
            if (file_exists($tempFile)) {
                unlink($tempFile);
            }
        }
    }
}
