<?php

namespace App\View\Components\Data;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CsvTable extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public array $csv,
    ){ }

    
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.data.csv-table');
    }


    public function shouldRender(): bool
    {
        return !empty($this->csv['data']);
    }
}
