<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        private readonly int $receiverId,
        private readonly int $messageId,
        private readonly string $senderName,
        private readonly string $text,
    ) {
    }

    public function broadcastAs(): string
    {
        return 'send.message';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->messageId,
            'sender' => $this->senderName,
            'text' => $this->text,
        ];
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("users.{$this->receiverId}"),
        ];
    }
}
