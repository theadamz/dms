<?php

namespace App\View\Components\Inputs;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Vite;
use Illuminate\View\Component;

class Lov extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $label,
        public ?string $placeholder = null,
        public ?bool $isRequired = false,
        public ?string $hiddenElementId = null,
        public string $hiddenElementName,
        public ?string $hiddenElementValue = null,
        public ?string $elementId = null,
        public string $elementName,
        public ?string $elementValue = null,
        public ?string $searchButtonName = null,
        public ?bool $clearButtonShow = false,
        public ?string $clearButtonName = null,
        public string $url,
        public ?string $modalSize = "modal-lg",
        public ?string $dependencyElementName = null,
        public ?string $hiddenElementColumnName = 'id',
        public ?string $elementColumnName = 'name',
        public ?string $columnNameForDescription = null,
        public ?string $textHelper = null,
    ) {
        $this->placeholder = $placeholder ?? $label;
        $this->hiddenElementId = $hiddenElementId ?? $hiddenElementName;
        $this->elementId = $elementId ?? $elementName;
        $this->searchButtonName = $searchButtonName ?? $hiddenElementName . 'LOV';
        $this->clearButtonName = $clearButtonName ?? $hiddenElementName . 'LOVClear';
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $data['jsFile'] = Vite::asset('resources/js/general.js');

        return view('components.inputs.lov')->with(compact('data'));
    }
}
