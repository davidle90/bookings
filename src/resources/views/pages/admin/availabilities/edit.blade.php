@extends('layouts.admin.main')

@section('styles')
@endsection

@section('modals')
    @if(isset($availability))
        @include('bookings::pages.admin.availabilities.modals.delete')
    @endif
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
            <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2">EDIT</span>
        </div>
    </li>
@endsection

@section('sidebar')
    <div class="w-1/6 p-5 border-r">
        <ul class="mx-2">
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
        <h1>Availabilities EDIT</h1>
        <form id="onSaveForm" action="{{ route('admin.bookings.availabilities.store') }}" method="POST" class="space-y-4">
            @csrf

            <label class="block font-semibold">Select Bookable:</label>
            <select id="bookable" name="bookable" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                <option selected>Choose a bookable</option>
                @foreach($bookables as $bookable)
                    <option value="{{ $bookable->id }} @if(isset($availability) && $availability->bookable == $bookable) selected @endif">{{ $bookable->name }}</option>
                @endforeach
            </select>
        
            <!-- Select Days -->
            <label class="block font-semibold">Select Available Days:</label>
            <div class="flex space-x-2">
                <label><input type="checkbox" name="days_of_week[]" value="monday"> Monday</label>
                <label><input type="checkbox" name="days_of_week[]" value="tuesday"> Tuesday</label>
                <label><input type="checkbox" name="days_of_week[]" value="wednesday"> Wednesday</label>
                <label><input type="checkbox" name="days_of_week[]" value="thursday"> Thursday</label>
                <label><input type="checkbox" name="days_of_week[]" value="friday"> Friday</label>
            </div>
        
            <!-- Start and End Time -->
            <label class="block font-semibold">Define Time Range:</label>
            <input type="time" name="start_time" required value="08:00">
            <input type="time" name="end_time" required value="17:00">
        
            <!-- Slot Duration -->
            <label class="block font-semibold">Slot Duration (minutes):</label>
            <input type="number" name="slot_duration" value="60" class="border p-2 rounded">
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
@endsection
