@extends('layouts.admin.main')

@section('styles')
@endsection

@section('modals')
    @if(isset($bookable))
        @include('bookings::pages.admin.bookables.modals.delete')
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
            <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2">Bookables</span>

            <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
            </svg>
            @if(isset($bookable))
                <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2">Edit</span>
            @else
                <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2">Create</span>
            @endif
        </div>
    </li>
@endsection

@section('sidebar')
    <div class="w-1/6 p-5 border-r">
        <ul class="mx-2 flex flex-col gap-2">
            <li>
                <a href="{{ route('admin.bookings.bookables.index') }}" class="text-blue-600 hover:text-blue-800">
                    Back
                </a>
            </li>
            @if(isset($bookable))
                <li>
                    <a href="{{ route('admin.bookings.availabilities.edit', ['bookable_id' => $bookable->id]) }}" class="text-blue-600 hover:text-blue-800">
                        Availabilities
                    </a>
                </li>
            @endif
        </ul>
    </div>
@endsection

@section('content')
    <div class="p-5">
        <h1 class="mb-5 text-xl font-semibold">Bookable</h1>
        <form id="onSaveForm" method="POST" action="{{ route('admin.bookings.bookables.store') }}" class="max-w-sm" autocomplete="off">

            @csrf

            <input type="hidden" name="id" value="{{ $bookable->id ?? '' }}">

            <div class="mb-5">
                <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Namn</label>
                <input type="text" id="name" name="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" value="{{ $bookable->name ?? '' }}">
            </div>

            <div class="mb-5">
                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" value="1" class="sr-only peer" name="is_active" @if(isset($bookable) && $bookable->is_active) checked @endif>
                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    <span class="ms-3 text-sm font-medium text-gray-900">Activate</span>
                </label>
            </div>
            
            <!-- attributes -->
        </form>

        <!-- SAVE/DELETE -->
        <ul class="flex text-gray-900">
            <li class="mb-5 mr-2">
                <button class="onSave block text-white bg-green-600 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center" type="button">
                    Spara
                </button>
            </li>
            @if(isset($bookable))
                <li class="mb-5">
                    <button data-modal-target="bookableDeleteModal" data-modal-toggle="bookableDeleteModal" class="block text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center" type="button">
                        Ta bort
                    </button>
                </li>
            @endif
        </ul>
    </div>
@endsection

@section('scripts')
    @include('bookings::includes.scripts.form');
@endsection
