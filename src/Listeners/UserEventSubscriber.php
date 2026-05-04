<?php

namespace Zephyrisle\AutoAvatar\Listeners;

use Flarum\User\Event\Activated;
use Flarum\User\Event\Registered;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Queue\Queue;
use Zephyrisle\AutoAvatar\Jobs\GenerateAvatarJob;

class UserEventSubscriber
{
    protected Queue $queue;

    public function __construct(Queue $queue)
    {
        $this->queue = $queue;
    }

    public function subscribe(Dispatcher $events): void
    {
        $events->listen(Registered::class, [$this, 'whenUserRegistered']);
        $events->listen(Activated::class, [$this, 'whenUserActivated']);
    }

    public function whenUserRegistered(Registered $event): void
    {
        $this->dispatchJob($event->user->id);
    }

    public function whenUserActivated(Activated $event): void
    {
        $this->dispatchJob($event->user->id);
    }

    protected function dispatchJob(int $userId): void
    {
        $this->queue->push(new GenerateAvatarJob($userId));
    }
}
