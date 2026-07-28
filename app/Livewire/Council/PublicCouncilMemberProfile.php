<?php

declare(strict_types=1);

namespace App\Livewire\Council;

use App\Domains\Municipality\Contracts\CouncilMemberRepositoryInterface;
use App\Domains\Municipality\Enums\CouncilMemberPosition;
use App\Domains\Municipality\Models\CouncilMember;
use Livewire\Component;

final class PublicCouncilMemberProfile extends Component
{
    public CouncilMember $member;
    public ?CouncilMember $previous = null;
    public ?CouncilMember $next = null;

    public function mount(CouncilMember $councilMember): void
    {
        $this->member = $councilMember->loadMissing('creator', 'updater');

        if (!$this->member->is_public || $this->member->status !== 'active') {
            abort(404);
        }

        $this->loadNavigation();
    }

    private function loadNavigation(): void
    {
        $repo = app(CouncilMemberRepositoryInterface::class);

        $this->previous = CouncilMember::where('is_public', true)
            ->where('status', 'active')
            ->where('display_order', '<', $this->member->display_order)
            ->orderBy('display_order', 'desc')
            ->first();

        $this->next = CouncilMember::where('is_public', true)
            ->where('status', 'active')
            ->where('display_order', '>', $this->member->display_order)
            ->orderBy('display_order')
            ->first();
    }

    public function render()
    {
        $posLabel = CouncilMemberPosition::tryFrom($this->member->position)?->label() ?? $this->member->position;

        $municipalityName = 'بلدية إذنا';
        try {
            $municipality = \App\Domains\Municipality\Models\Municipality::first();
            if ($municipality) {
                $municipalityName = $municipality->name_ar ?? $municipalityName;
            }
        } catch (\Throwable $e) {
            // Fail silently
        }

        $hasContact = $this->member->phone || $this->member->mobile || $this->member->email
            || $this->member->facebook || $this->member->twitter || $this->member->linkedin;

        return view('livewire.council.public-council-member-profile', [
            'posLabel' => $posLabel,
            'hasContact' => $hasContact,
            'municipalityName' => $municipalityName,
        ])->layout('layouts.home', [
            'title' => $this->member->full_name . ' - المجلس البلدي | ' . $municipalityName,
            'metaDescription' => $this->member->bio ?? 'ملف ' . $this->member->full_name . ' - ' . $posLabel . ' في بلدية إذنا',
        ]);
    }
}
