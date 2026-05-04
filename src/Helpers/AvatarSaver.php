<?php

namespace Zephyrisle\AutoAvatar\Helpers;

use Flarum\User\AvatarUploader;
use Flarum\User\User;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AvatarSaver
{
    protected AvatarUploader $uploader;

    public function __construct(AvatarUploader $uploader)
    {
        $this->uploader = $uploader;
    }

    /**
     * Save a raw image string to a user's avatar.
     *
     * @param User $user
     * @param string $content Raw image content
     * @param string $extension File extension (e.g., 'png', 'jpg')
     */
    public function save(User $user, string $content, string $extension = 'png'): void
    {
        // Create a temporary file
        $tempFile = tempnam(sys_get_temp_dir(), 'avatar');
        file_put_contents($tempFile, $content);

        // Wrap in UploadedFile for AvatarUploader
        $uploadedFile = new UploadedFile(
            $tempFile,
            'avatar.' . $extension,
            'image/' . ($extension === 'svg' ? 'svg+xml' : $extension),
            null,
            true // Test mode to allow local files
        );

        try {
            // Use Flarum's built-in uploader to handle resizing and storage
            $this->uploader->upload($user, $uploadedFile);
            $user->save();
        } finally {
            // Clean up temporary file
            if (file_exists($tempFile)) {
                unlink($tempFile);
            }
        }
    }
}
