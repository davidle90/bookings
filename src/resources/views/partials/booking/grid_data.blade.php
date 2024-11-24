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
            $dayBookings = $bookings->get($formatted_date) ?? [];
            $day_of_week = strtolower($date->format('l')); // Convert to lowercase once here
            $availability = $selected_bookable ? $selected_bookable->availabilities->firstWhere('day_of_week', $day_of_week) : null; // Find matching availability for the day
            $timeSlotsAvailable = $availability ? $availability->generateTimeSlots() : []; // Generate time slots for the availability
        @endphp
        <div class="border rounded h-24 p-2 flex flex-col justify-between cursor-pointer hover:bg-blue-50 @if(isset($availability) && $availability->day_of_week == $day_of_week) get_time_slots @endif" data-date="{{ $formatted_date }}">
            <div class="text-gray-700 font-semibold">{{ $day }}</div>

            @foreach ($dayBookings as $booking)
                <div class="text-xs bg-blue-500 text-white rounded px-2 py-1 mt-1">
                    {{ $booking->resource->name ?? 'Unknown' }} <br>
                    <small>{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}</small>
                </div>
            @endforeach

            @if($availability) <!-- If availability exists for this day -->
                @if(!empty($timeSlotsAvailable))
                    <div class="text-xs text-green-500">Available</div>
                @else
                    <div class="text-xs text-red-500">Fully booked</div>
                @endif
            @endif
        </div>
    @endfor
</div>

@section('modals')
    @include('bookings::partials.booking.modals.time_slots')
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            var modal = $('#timeSlotsModal');

            $(document).on('click', '.get_time_slots', function () {
                console.log('asd'); // This should log to the console when a .get_time_slots element is clicked
            });
        });
    </script>
@endpush