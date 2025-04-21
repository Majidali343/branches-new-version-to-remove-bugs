<?php

namespace App\Interfaces;

interface NotificationRepositoryInterface
{
    public function sendNotification(string $deviceToken, string $title, string $body);
}