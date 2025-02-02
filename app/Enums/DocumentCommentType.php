<?php

namespace App\Enums;

enum DocumentCommentType: string
{
    case COMMENT = 'comment';
    case REVIEW = 'review';
    case ACKNOWLEDGE = 'acknowledge';
}
