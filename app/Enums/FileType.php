<?php

namespace App\Enums;

enum FileType: string
{
    case PDF = 'pdf';
    case DOC = 'doc';
    case DOCX = 'docx';
    case XLS = 'xls';
    case XLSX = 'xlsx';
    case PPT = 'ppt';
    case PPTX = 'pptx';
    case PNG = 'png';
    case JPG = 'jpg';
    case JPEG = 'jpeg';

    public function getIcon(): string
    {
        return match ($this) {
            self::PDF => 'far fa-file-pdf',
            self::DOC => 'far fa-file-word',
            self::DOCX => 'far fa-file-word',
            self::XLS => 'far fa-file-excel',
            self::XLSX => 'far fa-file-excel',
            self::PPT => 'far fa-file-powerpoint',
            self::PPTX => 'far fa-file-powerpoint',
            self::PNG => 'far fa-file-image',
            self::JPG => 'far fa-file-image',
            self::JPEG => 'far fa-file-image',
            default => 'far fa-file',
        };
    }
}
