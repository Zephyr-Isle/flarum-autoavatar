<?php

/*
 * This file is part of zephyrisle/flarum-autoavatar.
 *
 * Copyright (c) 2026 Zephyr Isle.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Zephyrisle\AutoAvatar;

use Flarum\Extend;
use Zephyrisle\AutoAvatar\Listeners\UserEventSubscriber;

return [
    (new Extend\Frontend('admin'))
        ->js(__DIR__.'/js/dist/admin.js')
        ->css(__DIR__.'/resources/less/admin.less'),

    new Extend\Locales(__DIR__.'/resources/locale'),

    (new Extend\Event())
        ->subscribe(UserEventSubscriber::class),

    (new Extend\Settings())
        ->default('zephyrisle-autoavatar.mode', 'letter')
        ->default('zephyrisle-autoavatar.letter_bg_color', '#2196f3')
        ->default('zephyrisle-autoavatar.letter_text_color', '#ffffff')
        ->default('zephyrisle-autoavatar.letter_shape_circle', true)
        ->default('zephyrisle-autoavatar.api_url', 'https://picsum.photos/200')
        ->default('zephyrisle-autoavatar.api_fallback_url', ''),
];
