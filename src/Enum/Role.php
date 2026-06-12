<?php

declare(strict_types=1);

enum Role: string
{
    case ADMIN = 'ADMIN';
    case PHARMACIEN = 'PHARMACIEN';
    case PREPARATEUR = 'PREPARATEUR';
    case GESTIONNAIRE = 'GESTIONNAIRE';
}
