<?php


namespace App\Listener;


use App\Event\UserCreatedEvent;

class UserCreatedListener
{
    public function onUserCreated(UserCreatedEvent $event)
    {
        $event->getUser();
    }
}