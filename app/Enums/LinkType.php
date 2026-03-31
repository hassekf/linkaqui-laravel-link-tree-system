<?php

namespace App\Enums;

enum LinkType: string
{
    case Link = 'link';
    case Heading = 'heading';
    case Divider = 'divider';
}
