<div class="grid grid-cols-7 gap-2">
    <!-- Days of the Week -->
    <div class="text-center font-medium text-gray-600">Mon</div>
    <div class="text-center font-medium text-gray-600">Tue</div>
    <div class="text-center font-medium text-gray-600">Wed</div>
    <div class="text-center font-medium text-gray-600">Thu</div>
    <div class="text-center font-medium text-gray-600">Fri</div>
    <div class="text-center font-medium text-gray-600">Sat</div>
    <div class="text-center font-medium text-gray-600">Sun</div>

    <!-- Empty cells for days before the first of the month -->
    @php
        $startDayOfWeek = ($startOfMonth->dayOfWeekIso - 1); // Monday is 1, subtract 1 for zero-based index
    @endphp
    @for ($i = 0; $i < $startDayOfWeek; $i++)
        <div class="bg-gray-100 border rounded h-24"></div>
    @endfor

    <!-- Days of the Month -->
    @for ($day = 1; $day <= $endOfMonth->day; $day++)
        @php
            $date = \Carbon\Carbon::create($year, $month, $day);
            $formatted_date = $date->format('Y-m-d');
            // $dayBookings = $bookings->get($formatted_date) ?? [];
            $day_of_week = strtolower($date->format('l'));
            $availability = $selected_bookable ? $selected_bookable->availabilities->firstWhere('day_of_week', $day_of_week) : null;

            $time_slots = [];

            if ($availability) {
                $time_slots = array_merge($time_slots, $availability->generateTimeSlots($date));
            }

            $has_available_exception = false;

            if($available_exceptions){
                foreach($available_exceptions as $available_exception){
                    $formatted_start = Carbon\Carbon::parse($available_exception->start_datetime)->format('Y-m-d');
                    $formatted_end = Carbon\Carbon::parse($available_exception->start_datetime)->format('Y-m-d');
                    $formatted_day = $date->endOfDay()->format('Y-m-d');

                    if(($formatted_start >= $formatted_day) && ($formatted_end <= $formatted_day)){
                        $has_available_exception = true;

                        $time_slots = array_merge($time_slots, $available_exception->generateTimeSlots($date));
                    }
                }
            }

            $available = isset($availability) &&
                $availability->day_of_week == $day_of_week &&
                $date >= now()->startOfDay() && !empty($time_slots) ||
                $has_available_exception ? true : false;

        @endphp
        
        <div class="border rounded h-24 p-2 flex flex-col justify-between hover:bg-blue-50 @if($available) cursor-pointer get_time_slots @endif" data-date="{{ $formatted_date }}">
            <div class="text-gray-700 font-semibold">{{ $day }}</div>

            <!-- Admin view -->
            {{-- @foreach ($dayBookings as $booking)
                <div class="text-xs bg-blue-500 text-white rounded px-2 py-1 mt-1">
                    {{ $booking->resource->name ?? 'Unknown' }} <br>
                    <small>{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}</small>
                </div>
            @endforeach --}}

            @if($available)
                <div class="text-xs text-green-500">Available</div>
            @endif
        </div>
    @endfor
</div>

@push('scripts')
    <script>
        $(document).ready(function () {
            var $modal = $('#time_slots_modal');
            let bookable_id = null;
            let bookable_date = null;

            $(document).on('click', '.get_time_slots', function () {

                bookable_id = $('select[name=bookable_id]').val();
                bookable_date = $(this).data('date');                

                $('#time_slots_modal').removeClass('hidden').addClass('flex');
                get_time_slots(bookable_id, bookable_date);
            });

            $modal.on('click', '.close_modal', function () {
                $('#time_slots_modal').removeClass('flex').addClass('hidden');
            });

            $modal.on('click', '.relative', function (e) {
                e.stopPropagation();
            });

            function get_time_slots(bookable_id, bookable_date){
                $.ajax({
                    url: '{{ route('admin.bookings.get_time_slots') }}',
                    method: 'GET',
                    data: {
                        bookable_id,
                        bookable_date
                    },
                    cache: false,
                    dataType: 'json',
                    success: function (res) {
                        if(res.status == 1){
                            $modal.find('#time_slot_data').html(res.html);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error fetching data:', error); 
                    }
                });
            }
        });
    </script>
@endpush