<?php

namespace App\View\Components\Inputs;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Picture extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $label,
        public ?bool $noLabel = false,
        public ?bool $isRequired = false,
        public ?string $elementId = null,
        public string $elementName,
        public ?string $class = "img-thumbnail w-100",
        public ?string $pictureSource = null,
        public ?string $noPictureUrl = null,
        public ?string $accept = "image/jpg, image/jpeg, image/png, image/bmp, image/gif, image/svg, image/webp",
        public ?string $textHelper = null,
        public ?string $imgStyle = "width:100%;",
    ) {
        $this->noPictureUrl = $noPictureUrl ?? url('/assets/images/noimage.jpg');
        $this->pictureSource = $pictureSource ?? $noPictureUrl;
        $this->elementId = $elementId ?? $elementName;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.inputs.picture');
    }
}
