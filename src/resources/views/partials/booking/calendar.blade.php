<div class="md:p-5">
    <h1 class="text-2xl font-bold mb-6">Calendar</h1>

    <input type="hidden" name="month" value={{ $month }}>
    <input type="hidden" name="year" value={{ $year }}>

    <!-- Month Navigation -->
    <div class="flex justify-between items-center mb-6">
        <button type="button"
        class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 text-gray-700 prevMonth">
            &larr; Previous
        </button>
        <h2 class="text-lg font-semibold yearMonth">{{ \Carbon\Carbon::create($year, $month)->format('F Y') }}</h2>
        <button type="button"
        class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 text-gray-700 nextMonth">
            Next &rarr;
        </button>
    </div>

    <div id="grid_data">
        @include('bookings::partials.booking.grid_data')
    </div>

</div>

@push('scripts')
    <script>
        $(document).ready(function () {

            let bookable_id = null;
            
            let month = $('input[name=month]').val();
            let year = $('input[name=year]').val();
            
            $('.prevMonth').on('click', function () {
                let date = new Date(year, month - 1 - 1, 1); // Calculate the previous month
                month = date.getMonth() + 1; // JavaScript months are 0-based
                year = date.getFullYear();

                bookable_id = $('select[name=bookable_id]').val();

                update_calendar(month, year, date)
                get_grid_data(bookable_id, month, year);
            });

            $('.nextMonth').on('click', function () {
                let date = new Date(year, month - 1 + 1, 1);
                month = date.getMonth() + 1;
                year = date.getFullYear();

                bookable_id = $('select[name=bookable_id]').val();

                update_calendar(month, year, date)
                get_grid_data(bookable_id, month, year);
            });

            $('select[name=bookable_id]').on('change', function () {
                bookable_id = $(this).val();
                get_grid_data(bookable_id, month, year);
            });
        });

        function update_calendar(month, year, date){
            // Update hidden inputs
            $('input[name=month]').val(month);
            $('input[name=year]').val(year);

            // Format the month and year for display
            const formattedDate = date.toLocaleString('en-US', { month: 'long', year: 'numeric' });
            $('.yearMonth').html(formattedDate);
        }

        function get_grid_data(bookable_id, month, year){
            $.ajax({
                url: '{{ route('admin.bookings.get_bookings') }}',
                method: 'GET',
                data: {
                    bookable_id,
                    month,
                    year
                },
                cache: false,
                dataType: 'json',
                success: function (res) {
                    $('#grid_data').html(res.grid_data);
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching data:', error); 
                }
            });
        }
    </script>
@endpush