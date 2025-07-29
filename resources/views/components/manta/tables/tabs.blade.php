@props(['tablistShow', 'trashed'])
<flux:tabs class="mt-8 px-4" wire:model="tablistShow">
    <flux:tab name="general" wire:click="$set('tablistShow','general')">Actief</flux:tab>
    <flux:tab name="trashed" wire:click="$set('tablistShow','trashed')">Verwijderd <flux:badge color="zinc">
            {{ $trashed }}</flux:badge>
    </flux:tab>
</flux:tabs>
