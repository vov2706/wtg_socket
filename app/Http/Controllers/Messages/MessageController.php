<?php

namespace App\Http\Controllers\Messages;

use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Messages\StoreRequest;
use App\Http\Resources\Messages\MessageResource;
use App\Models\Message;
use App\Services\Messages\MessageService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;

class MessageController extends Controller
{
    public function __construct(
        private readonly MessageService $messageService
    ) {
    }

    public function index(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', 'string', new Enum(MessageType::class)],
        ]);

        return MessageResource::collection(
            $this->messageService->paginateMessages(
                MessageType::from($validated['type']),
                $request->user()
            )
        );
    }

    public function store(StoreRequest $request)
    {
        if ($request->user()->id === $request->validated('receiver_id')) {
            return $this->responseError('You cannot send a message to yourself!');
        }

        $this->messageService->notify($request->user(), $request->validated());

        return $this->responseSuccess('Message sent successfully!');
    }

    public function read(Message $message)
    {
        if ($message->receiver_id !== auth()->id()) {
            abort(403);
        }

        $this->messageService->readMessage($message);

        return $this->responseSuccess();
    }
}
