<?php

namespace Parallax\FilamentComments\Actions;

use Filament\Actions\Action;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Contracts\View\View;
use Parallax\FilamentComments\Models\FilamentComment;

class CommentsAction extends Action
{
    public $guard = 'web';

    public $notifyFrom;

    public $notifyTo;

    public static function getDefaultName(): ?string
    {
        return 'comments';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->hiddenLabel()
            ->icon(config('filament-comments.icons.action'))
            ->color('gray')
            ->badge($this->record->filamentComments()->count())
            ->slideOver()
            ->modalContentFooter(fn($livewire): View => view('filament-comments::component',
                [
                    'record' => $this->record,
                    'guard' => $this->getGuard(),
                    'notifyFrom' => $this->getNotifyFrom(),
                    'notifyTo' => $this->getNotifyTo(),
                ]
            )
            )
            ->modalHeading(__('filament-comments::filament-comments.modal.heading'))
            ->modalWidth(MaxWidth::Medium)
            ->modalSubmitAction(false)
            ->modalCancelAction(false)
            ->visible(fn(): bool => auth()->user()->can('viewAny', config('filament-comments.comment_model')));
    }

    public function guard(string $guard): static
    {
        $this->guard = $guard;

        return $this;
    }

    public function getGuard()
    {
        return $this->guard;
    }

    public function notifyFrom($notifyFrom): static
    {
        $this->notifyFrom = $notifyFrom;

        return $this;
    }

    public function notifyTo($notifyTo): static
    {
        $this->notifyTo = $notifyTo;

        return $this;
    }

    public function getNotifyFrom()
    {
        return $this->notifyFrom;
    }

    public function getNotifyTo()
    {
        return $this->notifyTo;
    }
}
