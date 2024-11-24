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
        <h1>BOOKING</h1>
        <form id="onSaveForm" action="{{ route('admin.bookings.availabilities.store') }}" method="POST" class="space-y-4">
            @csrf

            <input type="hidden" name="booking_id" value="{{ $booking->id ?? '' }}">

            <div class="border p-4">
                <h1 class="text-xl text-center mb-5">Select Bookable:</h1>
                <div>
                    <ul class="grid w-full gap-6">
                        @foreach($bookables as $bookable)
                            <li>
                                <input type="radio" id="{{ $bookable->slug }}" name="bookable" value="{{ $bookable->id }}" class="hidden peer"
                                    @if(isset($booking) && $booking->resource_id == $bookable->id && $booking->resource_type == get_class($bookable))
                                        checked
                                    @endif
                                />
                                <label for="{{ $bookable->slug }}" class="inline-flex items-center justify-between w-full p-5 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100">
                                    <div class="block">
                                        <div class="w-full text-lg font-semibold">{{ $bookable->name }}</div>
                                        <div class="w-full"></div>
                                    </div>
                                </label>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            @include('bookings::partials.booking.calendar')
        
        </form>
        
        <!-- SAVE/DELETE -->
        <ul class="flex text-gray-900 mt-5">
            <li class="mb-5 mr-2">
                <button class="onSave block text-white bg-green-600 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center" type="button">
                    Spara
                </button>
            </li>
            @if(isset($availability))
                <li class="mb-5">
                    <button data-modal-target="availabilityDeleteModal" data-modal-toggle="availabilityDeleteModal" class="block text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center" type="button">
                        Ta bort
                    </button>
                </li>
            @endif
        </ul>
    </div>
@endsection

@section('scripts')
    @include('bookings::includes.scripts.form')

    <script>
        $(document).ready(function () {
            
        });
    </script>
@endsection
