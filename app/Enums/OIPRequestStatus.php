<?php

namespace App\Enums;

enum OIPRequestStatus : string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
}
