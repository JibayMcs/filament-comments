<?php

namespace Parallax\FilamentComments\Livewire;

use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Parallax\FilamentComments\Models\FilamentComment;

class CommentsComponent extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public Model $record;

    public $guard = 'web';

    public $notifyFrom = null;

    public $notifyTo = null;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {

        if (!auth()->guard($this->guard)->user()->can('create', config('filament-comments.comment_model'))) {
            return $form;
        }

        if (config('filament-comments.editor') === 'markdown') {
            $editor = Forms\Components\MarkdownEditor::make('comment')
                ->hiddenLabel()
                ->required()
                ->placeholder(__('filament-comments::filament-comments.comments.placeholder'))
                ->toolbarButtons(config('filament-comments.toolbar_buttons'));
        } else {
            $editor = Forms\Components\RichEditor::make('comment')
                ->hiddenLabel()
                ->required()
                ->placeholder(__('filament-comments::filament-comments.comments.placeholder'))
                ->extraInputAttributes(['style' => 'min-height: 6rem'])
                ->toolbarButtons(config('filament-comments.toolbar_buttons'));
        }

        return $form
            ->schema([
                $editor,
            ])
            ->statePath('data');
    }

    public function create(): void
    {
        if (!auth()->guard($this->guard)->user()->can('create', config('filament-comments.comment_model'))) {
            return;
        }

        $this->form->validate();

        $data = $this->form->getState();

        $this->record->filamentComments()->create([
            'subject_type' => $this->record->getMorphClass(),
            'comment' => $data['comment'],
            'commentator_id' => auth()->guard($this->guard)->user()->id,
            'commentator_type' => auth()->guard($this->guard)->user()::class,
        ]);

        Notification::make()
            ->title(__('filament-comments::filament-comments.notifications.created'))
            ->success()
            ->send();

        $this->form->fill();
    }

    public function delete(int $id): void
    {
        $comment = FilamentComment::find($id);

        if (!$comment) {
            return;
        }

        if (!auth()->guard($this->guard)->user()->can('delete', $comment)) {
            return;
        }

        $comment->delete();

        Notification::make()
            ->title(__('filament-comments::filament-comments.notifications.deleted'))
            ->success()
            ->send();
    }

    public function render(): View
    {
        $comments = $this->record->filamentComments()->with(['commentator'])->latest()->get();

        return view('filament-comments::comments', ['comments' => $comments]);
    }

}
