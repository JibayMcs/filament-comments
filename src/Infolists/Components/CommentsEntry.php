<?php

namespace Parallax\FilamentComments\Infolists\Components;

use Filament\Infolists\Components\Entry;
use Illuminate\Database\Eloquent\Model;

class CommentsEntry extends Entry
{
    protected string $view = 'filament-comments::component';

    public $record;
    public $guard = 'web';

    public $notifyFrom = null;

    public $notifyTo = null;

    protected function setUp(): void
    {
        parent::setUp();


        $this->visible(fn (): bool => auth()->guard($this->guard)->user()->can('viewAny', config('filament-comments.comment_model')));
    }

    public function guard(string $guard): static
    {
        $this->guard = $guard;

        return $this;
    }

    public function record(Model|\Closure $record): static
    {
        $this->record = $record;

        return $this;
    }

    public function getGuard()
    {
        return $this->guard;
    }

    public function getCommentRecord()
    {
        return $this->record;
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
