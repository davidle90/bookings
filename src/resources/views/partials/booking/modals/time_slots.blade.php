<div id="time_slots_modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 min-h-screen">
    <div class="absolute inset-0 bg-black bg-opacity-50 close_modal"></div>
    <div class="relative p-4 w-full max-w-md max-h-full mx-auto">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal body -->
            <div class="p-4 md:p-5">

                <input type="hidden" name="bookable_id" value="">
                <input type="hidden" name="bookable_date" value="">

                <div id="time_slot_data">

                </div>

                <div class="flex justify-between">
                    <button class="close_modal text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                        Cancel
                    </button>
                    <button class="onReserve text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Reserve
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function () {
            let $modal = $('#time_slot_data');

            $('.onReserve').on('click', function() {

                let booking_id = $('input[name=booking_id]').val();
                let bookable_id = $('input[name=bookable]').val();
                let notes = $('textarea[name=notes]').val();
                let date = $modal.find('input[name=time_slot]').val();

                $.ajax({
                    url: '{{ route('admin.bookings.store') }}',
                    method: 'POST',
                    data: {
                        booking_id,
                        bookable_id,
                        notes,
                        date
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    cache: false,
                    dataType: 'json',
                    success: function (res) {
                        if(res.status == 1){
                            if(res.redirect){
                                window.location = res.redirect;
                            }
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error fetching data:', error); 
                    }
                });
                
            });
        });
    </script>
@endpush
  