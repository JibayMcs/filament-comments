<livewire:comments
    :record="$this->record ?? $getCommentRecord()"
    :guard="$this->guard ?? $getGuard()"
    :notifyFrom="$this->notifyFrom ?? $this->getNotifyFrom() ?? $getNotifyFrom()"
    :notifyTo="$this->notifyTo ?? $this->getNotifyTo() ?? $getNotifyTo()"
/>
