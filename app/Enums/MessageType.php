<?php

namespace App\Enums;

enum MessageType: string
{
    case SENT = 'sent';
    case RECEIVED = 'received';
}
