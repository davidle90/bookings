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
            <a href="{{ route('admin.index') }}" class="text-blue-600 hover:text-blue-800">
                Home
            </a>
        </li>
        <li>
            <a href="{{ route('admin.bookings.create') }}" class="text-blue-600 hover:text-blue-800">Create</a>
        </li>
        <li>
            <a href="{{ route('admin.bookings.bookables.index') }}" class="text-blue-600 hover:text-blue-800">Bookables</a>
        </li>
    </ul>
</div>
@endsection

@section('content')
    <div class="md:p-5">
        <h1 class="text-2xl font-bold mb-6">Bookings</h1>

        <!-- Month Navigation -->
        <div class="flex justify-between items-center mb-6">
            <a href="{{ route('admin.bookings.index', ['month' => $month - 1, 'year' => $year]) }}" 
            class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 text-gray-700">
                &larr; Previous
            </a>
            <h2 class="text-lg font-semibold">{{ \Carbon\Carbon::create($year, $month)->format('F Y') }}</h2>
            <a href="{{ route('admin.bookings.index', ['month' => $month + 1, 'year' => $year]) }}" 
            class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 text-gray-700">
                Next &rarr;
            </a>
        </div>

        <!-- Calendar Grid -->
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
                    $date = \Carbon\Carbon::create($year, $month, $day)->format('Y-m-d');
                    $dayBookings = $bookings->get($date) ?? [];
                @endphp
                <div class="border rounded h-24 p-2 flex flex-col justify-between">
                    <div class="text-gray-700 font-semibold">{{ $day }}</div>
                    @foreach ($dayBookings as $booking)
                        <div class="text-xs bg-blue-500 text-white rounded px-2 py-1 mt-1">
                            {{ $booking->resource->name ?? 'Unknown' }} <br>
                            <small>{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}</small>
                        </div>
                    @endforeach
                </div>
            @endfor
        </div>
    </div>
@endsection

@section('scripts')
@endsection
