@script()
    <script>
        console.log('Draggable initialized');
        Sortable.create(draggable, {
            handle: '.handle', // handle's class
            animation: 150,
            onEnd: function(evt) {
                let orderedIds = Array.from(evt.to.children).map(item => item.dataset.id);
                @this.call('updateRowOrder', orderedIds);
            }
        });
    </script>
@endscript
