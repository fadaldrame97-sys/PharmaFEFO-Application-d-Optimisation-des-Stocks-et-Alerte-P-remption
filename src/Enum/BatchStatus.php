<?php

declare(strict_types=1);

enum BatchStatus: string
{
    case OK = 'OK';
    case WARNING = 'WARNING';
    case CRITICAL = 'CRITICAL';
    case EXPIRED = 'EXPIRED';
    case AVAILABLE = 'AVAILABLE';
}
