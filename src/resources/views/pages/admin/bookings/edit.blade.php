@extends('layouts.admin.main')

@section('styles')
@endsection

@section('modals')
    @include('bookings::partials.booking.modals.time_slots')
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
            <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2">Edit</span>
        </div>
    </li>
@endsection

@section('sidebar')
    <div class="w-1/6 p-5 border-r">
        <ul class="mx-2 flex flex-col gap-2">
            <li>
                <a href="{{ route('admin.bookings.index') }}" class="text-blue-600 hover:text-blue-800">
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

            <input type="hidden" name="booking_id" value="{{ $booking->id ?? '' }}">

            <div class="flex justify-between gap-4">
                <div class="w-full">
                    <label for="bookable_id" class="block text-lg font-medium mb-2">Select Bookable:</label>
                    @if ($bookables->isEmpty())
                        <p class="text-red-500">No bookable items available.</p>
                    @else
                        <select name="bookable_id" id="bookable_id" class="w-full border rounded p-2">
                            <option value="">Select Bookable</option>
                            @foreach ($bookables as $bookable)
                                <option value="{{ $bookable->id }}">
                                    {{ $bookable->name }}
                                </option>
                            @endforeach
                        </select>
                    @endif
                </div>
                <div class="w-full">
                    <label for="notes" class="block mb-2 text-sm font-medium">Anteckningar:</label>
                    <textarea id="notes" name="notes" rows="6" class="w-full p-2.5 bg-gray-50 text-sm rounded-lg border" placeholder=""></textarea>
                </div>
            </div>
            

            @include('bookings::partials.booking.calendar')
        
        </form>
    </div>
@endsection

@section('scripts')
@endsection
