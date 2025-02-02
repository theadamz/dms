<?php

namespace App\Enums;

enum WorkflowType: string
{
    case INORDER = 'inorder';
    case DISORDER = 'disorder';

    public function getLabel(): string
    {
        return match ($this) {
            self::INORDER => 'In Order',
            self::DISORDER => 'Disorder',
        };
    }
}
