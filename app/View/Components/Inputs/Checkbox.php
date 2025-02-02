<?php

namespace App\View\Components\Inputs;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Checkbox extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $label,
        public ?string $checkLabel = null,
        public ?string $elementId = null,
        public string $elementName,
        public ?string $value = null,
        public ?int $maxLength = null,
        public ?string $textHelper = null,
    ) {
        $this->elementId = $elementId ?? $elementName;
        $this->checkLabel = $checkLabel ?? 'Yes';
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.inputs.checkbox');
    }
}
