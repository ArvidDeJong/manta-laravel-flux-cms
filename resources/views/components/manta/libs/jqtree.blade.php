<!-- HTML element voor jqTree -->
<div id="tree1" wire:ignore class="rounded-md bg-white p-4 shadow-md">
    <!-- Hier zal de boomstructuur worden weergegeven -->
</div>

@push('styles')
    <link rel="stylesheet"href="/libs/jqtree/jqtree.css" />
@endpush
@push('scripts')
    <script src="/libs/jqtree/jqtree.js"></script>
@endpush
@script
    <script>
        document.addEventListener('livewire:initialized', () => {
            console.log(@json($this->treeData));
            initializeTree(@json($this->treeData));
        });

        $wire.on('treeUpdated', function(treeData) {
            console.log(treeData['treeData']);
            $('#tree1').tree('loadData', treeData['treeData']);
        });

        function initializeTree(treeData) {
            var $tree = $('#tree1');
            $tree.tree({
                data: treeData,
                autoOpen: true,
                dragAndDrop: true,
                onCreateLi: function(node, $li) {
                    // Voeg een "edit" en "add child" knop toe aan elke node
                    $li.find('.jqtree-element').append(
                        '<div class="flex items-center">' +
                        // '<span class="mr-2">' + (node.children ? '+' : '-') + '</span>' +
                        '<span class="ml-2" style="color:black;">' + node.title + '</span>' +
                        '<a href="#node-' + node.id +
                        '" class="ml-2 text-yellow-500 edit hover:text-yellow-700" data-node-id="' + node
                        .id +
                        '"><i class="fa-solid fa-pen-to-square"></i></a>' +
                        '<a href="#node-' + node.id +
                        '" class="ml-2 text-green-500 add-child hover:text-green-700" data-node-id="' +
                        node.id + '"><i class="fa-solid fa-plus"></i></a>' +
                        '</div>'
                    );
                }
            });

            // Bind de "tree.move" event
            $tree.bind('tree.move', function(event) {
                event.preventDefault();
                event.move_info.do_move();
                let updatedData = $tree.tree('toJson'); // Serializeer de boom naar JSON
                console.log(updatedData);
                Livewire.dispatch('updateTreeData', {
                    data: updatedData
                });
            });

            // Bind de "click" event voor "edit" links
            $tree.on('click', '.edit', function(e) {
                console.log('Edit clicked');
                e.preventDefault(); // Voorkom dat de browser naar de link navigeert
                var node_id = $(this).data('node-id');
                var node = $tree.tree('getNodeById', node_id);

                console.log(node);
                if (node) {
                    // Trigger een Livewire actie met de node ID
                    Livewire.dispatch('editNode', {
                        node_id: node_id
                    });
                }
            });

            // Bind de "click" event voor "add child" knoppen
            $tree.on('click', '.add-child', function(e) {
                e.preventDefault();
                var node_id = $(this).data('node-id');
                var node = $tree.tree('getNodeById', node_id);
                if (node) {
                    console.log('Klik' + node_id);
                    // Trigger een Livewire actie om een kind toe te voegen aan het geselecteerde knooppunt
                    //    $set('parent_id', node_id);
                    Livewire.dispatch('addChildNode', {
                        parent_id: node_id
                    });
                }
            });
        }
    </script>
@endscript
