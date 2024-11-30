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
        </div>
    </li>
@endsection

@section('sidebar')
<div class="w-1/6 p-5 border-r">
    <ul class="mx-2 flex flex-col gap-2">
        <li>
            <a href="{{ route('admin.bookings.create') }}" class="text-blue-600 hover:text-blue-800">Book reservations</a>
        </li>
        <li>
            <a href="{{ route('admin.bookings.bookables.index') }}" class="text-blue-600 hover:text-blue-800">Bookables</a>
        </li>
        <li>
            <a href="{{ route('admin.bookings.availabilities.index') }}" class="text-blue-600 hover:text-blue-800">Availabilities</a>
        </li>
        <li>
            <a href="{{ route('admin.bookings.exceptions.index') }}" class="text-blue-600 hover:text-blue-800">Exceptions</a>
        </li>
    </ul>
</div>
@endsection

@section('content')
    <div class="p-5">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
            <thead class="text-xs text-gray-800 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3">ID</th>
                    <th scope="col" class="px-6 py-3">Booking</th>
                    <th scope="col" class="px-6 py-3">User</th>
                    <th scope="col" class="px-6 py-3">Date</th>
                    <th scope="col" class="px-6 py-3">Status</th>
                    <th scope="col" class="px-6 py-3">Notes</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookings as $booking)
                    <tr class="go-to-url cursor-pointer bg-white border-b">
                        <td class="px-6 py-4">{{ $booking->id }}</td>
                        <td class="px-6 py-4">{{ $booking->resource->name }}</td>
                        <td class="px-6 py-4">{{ $booking->user_id ?? '' }}</td>
                        <td class="px-6 py-4">{{ $booking->start_datetime }} - {{ $booking->end_datetime }}</td>
                        <td class="px-6 py-4">{{ $booking->status }}</td>
                        <td class="px-6 py-4">{{ $booking->notes ? 'Yes' : 'No' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{-- @include('bookings::partials.calendar') --}}
@endsection

@section('scripts')
@endsection
