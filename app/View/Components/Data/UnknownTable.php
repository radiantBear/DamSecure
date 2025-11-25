<?php

namespace App\View\Components\Data;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class UnknownTable extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public array $unknownData,
    ){ }

    
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.data.unknown-table');
    }


    public function shouldRender(): bool
    {
        return !empty($this->unknownData);
    }
}
