<?php

namespace App\Services\Messages;

use App\Enums\MessageType;
use App\Events\SendMessage;
use App\Models\Message;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use LogicException;

final readonly class MessageService
{
    public function paginateMessages(MessageType $type, User $user): LengthAwarePaginator
    {
        return $type === MessageType::SENT
            ? $this->paginateSentMessages($user)
            : $this->paginateReceivedMessages($user);
    }

    public function notify(User $user, array $data): void
    {
        /** @var Message $message */
        $message = $user->sentMessages()->create(Arr::only($data, ['receiver_id', 'text']));

        broadcast(new SendMessage(
            $message->receiver_id,
            $message->id,
            $user->name,
            $message->text
        ));
    }

    public function readMessage(Message $message): void
    {
        if ($message->read_at) {
            throw new LogicException('Message already read.');
        }

        $message->update(['read_at' => now()]);
    }

    private function paginateSentMessages(User $user): LengthAwarePaginator
    {
        return $user->sentMessages()
            ->with('receiver', 'sender')
            ->latest('id')
            ->paginate();
    }

    private function paginateReceivedMessages(User $user): LengthAwarePaginator
    {
        return $user->receivedMessages()
            ->with('receiver', 'sender')
            ->latest('id')
            ->paginate();
    }
}
