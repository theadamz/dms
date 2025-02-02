<?php

namespace App\Enums;

enum DocumentStatus: string
{
    case DRAFT = 'draft';
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case CANCELED = 'canceled';
    case RECALLED = 'recalled';

    public function getLabel(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::PENDING => 'Pending',
            self::APPROVED => 'Approved',
            self::REJECTED => 'Rejected',
            self::CANCELED => 'Canceled',
            self::RECALLED => 'Recalled',
            default => 'Unknown',
        };
    }

    public function getBadge(): string
    {
        return match ($this) {
            self::DRAFT => 'secondary',
            self::PENDING => 'warning',
            self::APPROVED => 'success',
            self::REJECTED => 'danger',
            self::CANCELED => 'danger',
            self::RECALLED => 'danger',
            default => 'secondary',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::DRAFT => 'fas fa-file',
            self::PENDING => 'fas fa-spinner',
            self::APPROVED => 'fas fa-check',
            self::REJECTED => 'fas fa-times',
            self::CANCELED => 'fas fa-times',
            self::RECALLED => 'fas fa-times',
            default => 'fas fa-question',
        };
    }

    public function isEditable(): bool
    {
        return match ($this) {
            self::DRAFT => true,
            default => false,
        };
    }
}
