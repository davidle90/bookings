@extends('layouts.admin.main')

@section('styles')
@endsection

@section('modals')
    @if(isset($exception))
        @include('bookings::pages.admin.exceptions.modals.delete')
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
            <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2">Exceptions</span>

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
                <a href="{{ route('admin.bookings.exceptions.index') }}" class="text-blue-600 hover:text-blue-800">
                    Back
                </a>
            </li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="p-5">
        <h1 class="mb-5 text-xl font-semibold">Exceptions</h1>
        <form id="onSaveForm" method="POST" action="{{ route('admin.bookings.exceptions.store') }}" class="max-w-sm" autocomplete="off">
            @csrf
            <input type="hidden" name="id" value="{{ $exception->id ?? '' }}">

            <div class="mb-5">
                <label for="label" class="block mb-2 text-sm font-medium text-gray-900">Label</label>
                <input type="text" id="label" name="label" value="{{ old('label', $exception->label ?? '') }}"
                       class="text-sm rounded border-gray-300 text-gray-900 bg-gray-50">
            </div>

            <div class="mb-5">
                <label for="bookable_id" class="block mb-2 text-sm font-medium text-gray-900">Bookable</label>
                <select name="bookable_id" id="bookable_id" class="text-sm rounded border-gray-300 text-gray-900 bg-gray-50">
                    <option value="">Select Bookable</option>
                    @foreach($bookables as $bookable)
                        <option value="{{ $bookable->id }}" @if(isset($exception) && $exception->bookable_id == $bookable->id) selected @endif>{{ $bookable->name }}</option>
                    @endforeach
                </select>
            </div>

            <div id="start_datetime" class="mb-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex flex-col gap-2">
                        <label for="start_datetime" class="text-sm font-medium text-gray-700">Start:</label>
                        <input 
                            type="datetime-local" 
                            id="start_datetime" 
                            name="start_datetime" 
                            value="{{ $exception->start_datetime ?? now()->format('Y-m-d H:i') }}" 
                            class="px-3 py-2 text-sm rounded-lg border border-gray-300 text-gray-900 bg-gray-50 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 ease-in-out"
                        >
                    </div>
                </div>
            </div>            

            <div id="end_datetime" class="mb-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex flex-col gap-2">
                        <label for="end_datetime" class="text-sm font-medium text-gray-700">End:</label>
                        <input 
                            type="datetime-local" 
                            id="end_datetime" 
                            name="end_datetime" 
                            value="{{ $exception->end_datetime ?? now()->format('Y-m-d H:i') }}" 
                            class="px-3 py-2 text-sm rounded-lg border border-gray-300 text-gray-900 bg-gray-50 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 ease-in-out"
                        >
                    </div>
                </div>
            </div> 

            <div class="mb-5">
                <label for="type" class="block mb-2 text-sm font-medium text-gray-900">Type</label>
                <select name="type" id="type" class="text-sm rounded border-gray-300 text-gray-900 bg-gray-50">
                    <option value="block" @if(isset($exception) && $exception->type == 'block') selected @endif>Block</option>
                    <option value="available" @if(isset($exception) && $exception->type == 'available') selected @endif>Available</option>
                </select>
            </div>

            <div class="mb-5">
                <label for="notes" class="block mb-2 text-sm font-medium">Notes:</label>
                <textarea id="notes" name="notes" rows="6" class="w-full p-2.5 bg-gray-50 border text-sm rounded-lg" placeholder=""></textarea>
            </div>

            <div class="mb-5">
                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" value="1" class="sr-only peer" name="is_global" @if(isset($exception) && $exception->is_global) checked @endif>
                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    <span class="ms-3 text-sm font-medium text-gray-900">Set as global exception</span>
                </label>
            </div>

            <!-- SAVE/DELETE -->
            <ul class="flex text-gray-900">
                <li class="mb-5 mr-2">
                    <button class="onSave block text-white bg-green-600 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center" type="button">
                        Save
                    </button>
                </li>
                @if(isset($exception))
                    <li class="mb-5">
                        <button data-modal-target="exceptionDeleteModal" data-modal-toggle="exceptionDeleteModal" class="block text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center" type="button">
                            Delete
                        </button>
                    </li>
                @endif
            </ul>
        </form>
    </div>
@endsection

@section('scripts')
    @include('bookings::includes.scripts.form') 
@endsection
