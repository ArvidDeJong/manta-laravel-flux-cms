@props([
    'item',
    // Delete props
    'title' => 'Delete item?',
    'message' => 'Weet je zeker dat je dit item wilt verwijderen?',
    'confirmText' => 'Delete',
    'cancelText' => 'Annuleren',
    'method' => 'deleteConfirm',
    // Restore props
    'restoreTitle' => 'Restore item?',
    'restoreMessage' => 'Weet je zeker dat je dit item wilt herstellen?',
    'restoreConfirmText' => 'Restore',
    'restoreMethod' => 'restoreConfirm',
])

@php
    $isTrashed = method_exists($item, 'trashed') && $item->trashed();
    $modalName = $isTrashed ? "restore-modal-{$item->id}" : "delete-modal-{$item->id}";
    $currentTitle = $isTrashed ? $restoreTitle : $title;
    $currentMessage = $isTrashed ? $restoreMessage : $message;
    $currentConfirmText = $isTrashed ? $restoreConfirmText : $confirmText;
    $currentMethod = $isTrashed ? $restoreMethod : $method;
    $buttonIcon = $isTrashed ? 'arrow-path' : 'trash';
    $buttonVariant = $isTrashed ? 'primary' : 'danger';
@endphp

<flux:modal.trigger name="{{ $modalName }}">
    <flux:button variant="{{ $buttonVariant }}" size="sm" icon="{{ $buttonIcon }}" />
</flux:modal.trigger>

<flux:modal name="{{ $modalName }}" class="min-w-[22rem]">
    <div class="space-y-6">
        <form wire:submit.prevent="{{ $currentMethod }}({{ $item->id }})">
            <div>
                <flux:heading size="lg">{{ $currentTitle }}</flux:heading>
                <flux:text class="mt-2">
                    <p>{{ $currentMessage }}</p>
                </flux:text>
            </div>
            <div class="flex gap-2">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">{{ $cancelText }}</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="{{ $buttonVariant }}">{{ $currentConfirmText }}</flux:button>
            </div>
        </form>
    </div>
</flux:modal>
