<x-layout>
@foreach (auth()->user()->notifications as $notification)
@php
        $isRequestRejected = $notification->type === 'App\Notifications\RequestRejected';
        $bgColor = $isRequestRejected ? 'bg-red-100' : 'bg-green-100';
        $borderColor = $isRequestRejected ? 'border-red-500' : 'border-green-500';
        $textColor = $isRequestRejected ? 'text-red-700' : 'text-green-700';
    @endphp
    <div class="notification {{ $bgColor }} border-l-4 {{ $borderColor }} {{ $textColor }} p-4 mb-4 rounded" role="alert">
        <p>{{ $notification->data['message'] }}</p>
        <small>{{ $notification->created_at->diffForHumans() }}</small>
    </div>
    @endforeach

    <div class="grid grid-cols-1 gap-4 px-4 mt-8 sm:grid-cols-4 sm:px-8">
        <!-- Today's Total Hours Card -->
        <div class="flex items-center bg-white border rounded-lg overflow-hidden shadow-lg transition-transform transform hover:scale-105">
            <div class="p-4 bg-green-500 rounded-l-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                    </path>
                </svg>
            </div>
            <div class="px-4 py-6 text-gray-800">
                <h3 class="text-sm font-semibold tracking-wider">Today Total Hours</h3>
                <p class="text-3xl font-bold">
                    @if($todayTotalHours)
                    @php
                    $hours = floor($todayTotalHours);
                    $minutes = floor(($todayTotalHours - $hours) * 60);
                    $seconds = floor((($todayTotalHours - $hours) * 60 - $minutes) * 60);
                    @endphp
                    {{ sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds) }}
                    @else
                    N/A
                    @endif
                </p>
            </div>
        </div>

        <!-- This Week Total Hours Card -->
        <div class="flex items-center bg-white border rounded-lg overflow-hidden shadow-lg transition-transform transform hover:scale-105">
            <div class="p-4 bg-blue-500 rounded-l-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                    </path>
                </svg>
            </div>
            <div class="px-4 py-6 text-gray-800">
                <h3 class="text-sm font-semibold tracking-wider">This Week Total Hours: </h3>
                <p class="text-3xl font-bold">
                    @if($weeklyTotalHours)
                    @php
                    $hours = floor($weeklyTotalHours);
                    $minutes = floor(($weeklyTotalHours - $hours) * 60);
                    $seconds = floor((($weeklyTotalHours - $hours) * 60 - $minutes) * 60);
                    @endphp
                    {{ sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds) }}
                    @else
                    N/A
                    @endif
                </p>
            </div>
        </div>

        <!-- This Month Total Hours Card -->
        <div class="flex items-center bg-white border rounded-lg overflow-hidden shadow-lg transition-transform transform hover:scale-105">
            <div class="p-4 bg-purple-500 rounded-l-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                    </path>
                </svg>
            </div>
            <div class="px-4 py-6 text-gray-800">
            <h3 class="text-sm font-semibold tracking-wider">This Month Total Hours </h3>
                <p class="text-3xl font-bold">
                    @if($monthlyTotalHours)
                    @php
                    $hours = floor($monthlyTotalHours);
                    $minutes = floor(($monthlyTotalHours - $hours) * 60);
                    $seconds = floor((($monthlyTotalHours - $hours) * 60 - $minutes) * 60);
                    @endphp
                    {{ sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds) }}
                    @else
                    N/A
                    @endif
                </p>
            </div>
        </div>

        <!-- Left Hours This Week Card -->
        <div class="flex items-center bg-white border rounded-lg overflow-hidden shadow-lg transition-transform transform hover:scale-105">
            <div class="p-4 bg-yellow-500 rounded-l-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 12l2.5 2.5L12 17l-2.5-2.5L12 12zm0 0l-2.5-2.5L12 7l2.5 2.5L12 12zM3 12h18M3 6h18M3 18h18" />
                </svg>
            </div>
            <div class="px-4 py-6 text-gray-800">
                <h3 class="text-sm font-semibold tracking-wider">Left Hours This Week </h3>
                <p class="text-3xl font-bold">
                    @php
                    // Ensure $leftHoursPerWeek is non-negative
                    $leftHoursPerWeek = max($leftHoursPerWeek, 0);

                    // Convert the decimal to hours and minutes
                    $hours = floor($leftHoursPerWeek); // Get whole hours
                    $minutes = floor(($leftHoursPerWeek - $hours) * 60); // Get remaining minutes
                    @endphp

                    {{ sprintf('%02d:%02d', $hours, $minutes) }} Hours
                </p>

            </div>
        </div>

        <!-- Left Hours This Month Card -->
        <div class="flex items-center bg-white border rounded-lg overflow-hidden shadow-lg transition-transform transform hover:scale-105">
            <div class="p-4 bg-red-500 rounded-l-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 12h14M5 6h14M5 18h14" />
                </svg>
            </div>
            <div class="px-4 py-6 text-gray-800">
                <h3 class="text-sm font-semibold tracking-wider">Left Hours This Month </h3>
                <p class="text-3xl font-bold">
                    @php
                    // Calculate left hours for the month
                    $leftHoursPerMonth = max($leftHoursPerMonth, 0);

                    // Convert the decimal to hours and minutes
                    $hours = floor($leftHoursPerMonth); // Get whole hours
                    $minutes = floor(($leftHoursPerMonth - $hours) * 60); // Get remaining minutes
                    @endphp
                    {{ sprintf('%02d:%02d', $hours, $minutes) }} Hours
                </p>
            </div>
        </div>
    </div>




    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 px-4 mt-8 sm:px-8">
        <!-- Attendance Records -->
        <div class="flex flex-col bg-white border rounded-lg shadow-md overflow-hidden">
            <div class="flex items-center p-4 bg-green-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <h3 class="ml-4 text-xl font-semibold text-white">Attendance Records</h3>
            </div>
            <div class="p-4">
                @if ($attendances->isEmpty())
                <p class="text-gray-600">No attendance records found.</p>
                @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Clock In</th>
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Clock Out</th>
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Total hours</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($attendances as $attendance)
                            <tr>
                                <td class="py-2 px-4 whitespace-nowrap text-gray-700">
                                    {{ $attendance->clock_in ? \Carbon\Carbon::parse($attendance->clock_in)->format('Y-m-d h:i:s A') : 'N/A' }}
                                </td>
                                <td class="py-2 px-4 whitespace-nowrap text-gray-700">
                                    {{ $attendance->clock_out ? \Carbon\Carbon::parse($attendance->clock_out)->format('h:i:s A') : 'N/A' }}
                                </td>
                                <td class="py-2 px-4 whitespace-nowrap text-gray-700">
                                    @if($attendance->total_hours)
                                    @php
                                    $hours = floor($attendance->total_hours);
                                    $minutes = floor(($attendance->total_hours - $hours) * 60);
                                    $seconds = floor((($attendance->total_hours - $hours) * 60 - $minutes) * 60);
                                    @endphp
                                    {{ sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds) }}
                                    @else
                                    N/A
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>

        <!-- Weekend Attendance Records -->
        <div class="flex flex-col bg-white border rounded-lg shadow-md overflow-hidden mt-4 md:mt-0">
            <div class="flex items-center p-4 bg-blue-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3" />
                </svg>
                <h3 class="ml-4 text-xl font-semibold text-white">Weekend Attendance Records</h3>
            </div>
            <div class="p-4">
                @if ($weekendAttendances->isEmpty())
                <p class="text-gray-600">No weekend attendance records found.</p>
                @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Clock In</th>
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Clock Out</th>
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Total hours</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($weekendAttendances as $weekendAttendance)
                            <tr>
                                <td class="py-2 px-4 whitespace-nowrap text-gray-700">
                                    {{ $weekendAttendance->clock_in ? \Carbon\Carbon::parse($weekendAttendance->clock_in)->format('Y-m-d h:i:s A') : 'N/A' }}
                                </td>
                                <td class="py-2 px-4 whitespace-nowrap text-gray-700">
                                    {{ $weekendAttendance->clock_out ? \Carbon\Carbon::parse($weekendAttendance->clock_out)->format('h:i:s A') : 'N/A' }}
                                </td>
                                <td class="py-2 px-4 whitespace-nowrap text-gray-700">
                                    @if($weekendAttendance->total_hours)
                                    @php
                                    $hours = floor($weekendAttendance->total_hours);
                                    $minutes = floor(($weekendAttendance->total_hours - $hours) * 60);
                                    $seconds = floor((($weekendAttendance->total_hours - $hours) * 60 - $minutes) * 60);
                                    @endphp
                                    {{ sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds) }}
                                    @else
                                    N/A
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>







</x-layout>