<?php

namespace App\Enums;

enum JoinStatus: int
{
    case Accepted = 1;
    case Pending = 2;
    case Declined = 3;
}
