<?php

namespace App\Livewire\OpenData;

use Livewire\Component;

final class Index extends Component
{
    public function render()
    {
        return view('livewire.open-data.index')->layout('layouts.home', [
            'title' => 'البيانات المفتوحة',
        ]);
    }
}
