<?php

enum JoinStatus: int
{
    case Accepted = 1;
    case Pending = 2;
    case Ignored = 3;
    case Declined = 4;
}
