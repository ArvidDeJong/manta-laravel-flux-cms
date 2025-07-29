@push('scripts')
    <script>
        $(function() {
            $("#sortable").sortable({
                handle: ".sorthandle",
                update: function(event, ui) {
                    var ids = $(this)
                        .sortable('toArray', {
                            attribute: 'data-id'
                        }).toString();
                    @this.sortingArray(ids);
                }
            });
        });
    </script>
@endpush
