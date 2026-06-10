<?php

enum BatchStatus
{
    case OK;
    case WARNING;
    case CRITICAL;
    case EXPIRED;
}