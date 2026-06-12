<?php
// src/Enum/BatchStatus.php
namespace PharmaFEFO\Enum;

enum BatchStatus: string {
    case OK = 'OK';
    case WARNING = 'WARNING';
    case CRITICAL = 'CRITICAL';
    case EXPIRED = 'EXPIRED';
}