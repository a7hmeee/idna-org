<?php

declare(strict_types=1);

namespace App\Livewire\WebsiteSettings;

use App\Domains\WebsiteSettings\Models\WebsiteSetting;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.dashboard')]
final class SettingsManager extends Component
{
    public string $editingKey = '';

    public string $editingValue = '';

    public string $editingType = 'text';

    public string $editingGroup = 'general';

    public bool $showEditModal = false;

    public function mount(): void
    {
        if (! auth()->user()->can('website_settings.view')) {
            abort(403);
        }
    }

    public function editSetting(WebsiteSetting $setting): void
    {
        if (! auth()->user()->can('website_settings.update')) {
            abort(403);
        }

        $this->editingKey = $setting->key;
        $this->editingValue = $setting->type === 'boolean' ? ($setting->value ? '1' : '') : (string) $setting->value;
        $this->editingType = $setting->type;
        $this->editingGroup = $setting->group;
        $this->showEditModal = true;
    }

    public function saveSetting(): void
    {
        if (! auth()->user()->can('website_settings.update')) {
            abort(403);
        }

        $this->validate([
            'editingKey' => ['required', 'string', 'max:255'],
            'editingValue' => ['required', 'string'],
        ]);

        $value = $this->editingType === 'boolean' ? ($this->editingValue ? '1' : '0') : $this->editingValue;

        WebsiteSetting::where('key', $this->editingKey)->update([
            'value' => $value,
        ]);

        $this->showEditModal = false;
        $this->reset(['editingKey', 'editingValue', 'editingType', 'editingGroup']);

        session()->flash('success', 'تم تحديث الإعداد بنجاح.');
    }

    public function closeEditModal(): void
    {
        $this->showEditModal = false;
        $this->reset(['editingKey', 'editingValue', 'editingType', 'editingGroup']);
    }

    public function render()
    {
        $settings = WebsiteSetting::orderBy('group')->orderBy('key')->get();
        $grouped = $settings->groupBy('group');

        return view('livewire.website-settings.settings-manager', [
            'groupedSettings' => $grouped,
        ]);
    }
}
