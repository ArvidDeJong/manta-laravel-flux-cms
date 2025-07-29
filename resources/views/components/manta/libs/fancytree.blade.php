@props(['depth' => 2])

<div id="tree" wire:ignore class="rounded-md bg-white p-4 shadow-md"></div>

@push('styles')
    <link href="/libs/fancytree-2.38.4/dist/skin-lion/ui.fancytree.min.css" rel="stylesheet">
    <link href="/libs/fancytree-2.38.4/dist/skin-win8/ui.fancytree.min.css" rel="stylesheet">
    <script src="/libs/fancytree-2.38.4/dist/jquery.fancytree-all-deps.min.js"></script>
@endpush

@script
    <script>
        document.addEventListener('livewire:initialized', () => {
            const maxDepth = {{ $depth }};

            const initializeFancyTree = (treeData) => {
                $("#tree").fancytree({
                    source: treeData,
                    extensions: ["dnd5"],
                    dnd5: {
                        preventVoidMoves: true,
                        preventRecursion: true,
                        dragStart: (node, data) => true,
                        dragEnter: (node, data) => handleDragEnter(node, data, maxDepth),
                        dragDrop: (node, data) => handleDragDrop(node, data, maxDepth),
                    },
                    activate: function(event, data) {
                        const node = data.node;
                        $wire.dispatch('editItem', {
                            itemId: node.key
                        });
                    }
                });
            };

            // Initialiseer FancyTree bij pagina-lading
            initializeFancyTree({!! json_encode($this->treeData) !!});

            // Luister naar Livewire dispatch-event om FancyTree opnieuw te laden
            Livewire.on('refreshTree', (eventData) => {
                const updatedTree = eventData[0].treeData; // Haal de nieuwe boomdata op

                // Vernieuw FancyTree met nieuwe data
                $("#tree").fancytree("destroy"); // Verwijder bestaande FancyTree instance
                initializeFancyTree(updatedTree); // Herinitialiseer met nieuwe data
            });

            function handleDragEnter(node, data, maxDepth) {
                const sourceLevel = data.otherNode.getLevel();
                const targetLevel = node.getLevel();

                if (sourceLevel === 1 && targetLevel === 1) {
                    return ["before", "after"];
                }

                if (targetLevel + 1 <= maxDepth) {
                    return true;
                }

                return false;
            }

            function handleDragDrop(node, data, maxDepth) {
                const targetLevel = node.getLevel();

                if (targetLevel < maxDepth) {
                    data.otherNode.moveTo(node, data.hitMode);
                }

                const tree = $.ui.fancytree.getTree("#tree");
                const updatedTreeData = tree.toDict(true);

                $wire.dispatch('updateTree', {
                    updatedTree: updatedTreeData
                });
            }
        });
    </script>
@endscript
