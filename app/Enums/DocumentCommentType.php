<?php

namespace App\Enums;

enum DocumentCommentType: string
{
    case COMMENT = 'comment';
    case APPROVAL = 'approval';
    case REVIEW = 'review';
    case ACKNOWLEDGE = 'acknowledge';
}
