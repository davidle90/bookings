@extends('layouts.admin.main')

@section('styles')
@endsection

@section('modals')
@endsection

@section('breadcrumbs')
    <li aria-current="page">
        <div class="flex items-center">
            <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
            </svg>
            <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2">Bookings</span>

            <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
            </svg>
            <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2">Availabilities</span>

            <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
            </svg>
            <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2">{{ $bookable->name }}</span>
        </div>
    </li>
@endsection

@section('sidebar')
    <div class="w-1/6 p-5 border-r">
        <ul class="mx-2">
            <li>
                <a href="{{ route('admin.bookings.availabilities.index') }}" class="text-blue-600 hover:text-blue-800">
                    Back
                </a>
            </li>
        </ul>
    </div>
@endsection

@section('content')

    <div class="p-5">
        <form id="onSaveForm" action="{{ route('admin.bookings.availabilities.store') }}" method="POST" class="space-y-4">
            @csrf

            <input type="hidden" name="bookable_id" value="{{ $bookable->id }}">

            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900" for="booking_type">Bokningstyp:</label>
                <select name="booking_type" id="booking_type" class="text-sm rounded border-gray-300 text-gray-900 bg-gray-50">
                    <option value="full_days" @if(isset($bookable) && !$bookable->availabilities->isEmpty() && $bookable->availabilities->first()->booking_type == 'full_dayes') selected @endif>Hela dagar</option>
                    <option value="timeslots" @if(isset($bookable) && !$bookable->availabilities->isEmpty() && $bookable->availabilities->first()->booking_type == 'timeslots') selected @endif>Tidslucka</option>
                </select>
            </div>

            <!-- Days of the Week and Availability Times -->
            <label class="block font-semibold">Select Available Days:</label>
            <div class="flex flex-col gap-4">
                @foreach($days as $day => $label)
                    <div class="flex flex-row gap-2">
                        <label class="block flex items-center gap-2">
                            <input 
                                type="checkbox" 
                                name="days_of_week[{{ $day }}][enabled]" 
                                value="1" 
                                class="day-checkbox"
                                data-day="{{ $day }}"
                                @if(isset($bookable) && $bookable->availabilities->contains('day_of_week', $day)) checked @endif
                            >
                            {{ $label }}
                        </label>

                        <!-- Time Inputs (Hidden by Default) -->
                        <div class="timeslots_booking hidden flex items-center gap-2 mt-1" data-day="{{ $day }}">
                            <!-- Start Time Input -->
                            <input 
                                type="time" 
                                name="days_of_week[{{ $day }}][start_time]" 
                                value="{{ old('days_of_week.' . $day . '.start_time', isset($bookable) && $bookable->availabilities->where('day_of_week', $day)->first() ? $bookable->availabilities->where('day_of_week', $day)->first()->start_time : '08:00') }}" 
                                class="border border-gray-300 rounded px-2 py-1 day-time day-time-start"
                                data-day="{{ $day }}"
                                @if(!old('days_of_week.' . $day . '.enabled') && !isset($bookable)) disabled @endif
                            >
                            <span>-</span>
                            <!-- End Time Input -->
                            <input 
                                type="time" 
                                name="days_of_week[{{ $day }}][end_time]" 
                                value="{{ old('days_of_week.' . $day . '.end_time', isset($bookable) && $bookable->availabilities->where('day_of_week', $day)->first() ? $bookable->availabilities->where('day_of_week', $day)->first()->end_time : '17:00') }}" 
                                class="border border-gray-300 rounded px-2 py-1 day-time day-time-end"
                                data-day="{{ $day }}"
                                @if(!old('days_of_week.' . $day . '.enabled') && !isset($bookable)) disabled @endif
                            >
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Slot Duration -->
            <div class="timeslots_booking hidden">
                <label class="block font-semibold">Slot Duration (minutes):</label>
                <input 
                    type="number" 
                    name="slot_duration" 
                    value="{{ $bookable->availabilities && $bookable->availabilities->first() ? $bookable->availabilities->first()->slot_duration : 60 }}" 
                    class="border p-2 rounded"
                >
            </div>
        </form>
        
        <!-- Save/Delete Buttons -->
        <ul class="flex text-gray-900 mt-5">
            <li class="mb-5 mr-2">
                <button 
                    class="onSave block text-white bg-green-600 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
                    type="button">
                    Save
                </button>
            </li>
        </ul>
        <div class="action-message text-red-600"></div>
    </div>
@endsection

@section('scripts')
    @include('bookings::includes.scripts.form')

    <script>
        $(document).ready(function () {
            if($('select[name=booking_type]').val() == 'full_days'){
                $('.timeslots_booking').addClass('hidden');
            } else {
                $('.timeslots_booking').removeClass('hidden');
            }

            $('select[name=booking_type]').on('change', function () {
                if($(this).val() == 'full_days'){
                    $('.timeslots_booking').addClass('hidden');
                }

                if($(this).val() == 'timeslots'){
                    $('.timeslots_booking').removeClass('hidden');
                }
            });
        });
    </script>
@endsection
