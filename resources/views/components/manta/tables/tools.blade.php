    @props(['item', 'delete' => null, 'deleteId', 'routename' => null])

    @if ($item->trashed())
        <button wire:click="restore('{{ addslashes(get_class($item)) }}','{{ $item->id }}')"
            class="inline-flex items-center px-3 py-1.5 bg-yellow-300 hover:bg-yellow-400 text-gray-800 text-xs font-medium rounded focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2"
            title="Zet terug">
            <i class="fa-solid fa-rotate-left"></i>
        </button>
    @elseif ($deleteId == null || $deleteId != $item->id)
        @if ($delete && (!isset($item->locked) || $item->locked == false))
            <button wire:click="delete('{{ addslashes(get_class($item)) }}','{{ $item->id }}')"
                class="inline-flex items-center px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                title="Verwijderen">
                <i class="fa-solid fa-trash-can"></i>
            </button>
        @endif
        <a href="{{ route($routename . '.read', [$routename => $item]) }}"
            class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            <i class="fa-solid fa-eye"></i>
        </a>
        {{ $slot }}
    @elseif($deleteId == $item->id)
        Verwijder?
        <button class="btn btn-sm btn-success" wire:click="deleteConfirm"><i
                class="fa-solid fa-check text-green-700"></i></button>
        <button class="btn btn-sm btn-danger" wire:click="deleteCancel"><i
                class="fa-solid fa-xmark text-red-700"></i></button>
        &nbsp; &nbsp;
    @endif
