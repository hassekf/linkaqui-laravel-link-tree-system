<?php

namespace App\Livewire\Builder;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.builder')]
#[Title('Page Builder')]
class PageBuilder extends Component
{
    public function render()
    {
        return view('livewire.builder.page-builder');
    }
}
