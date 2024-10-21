<x-layout>
    <div class="container mx-auto my-8 p-6 bg-white shadow-lg rounded-lg">
        <h1 class="text-2xl font-semibold mb-6 text-gray-800">Admin Dashboard - Pending Requests</h1>

        <!-- Display success or error messages -->
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <!-- Assuming you have a collection of requests to display -->
        @if ($requests->isEmpty())
            <p class="text-gray-600">No pending requests at the moment. Refresh to check latest</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white rounded-lg shadow overflow-hidden">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="py-3 px-4 text-left text-gray-600 font-medium">User Name</th>
                            <th class="py-3 px-4 text-left text-gray-600 font-medium">Request Type</th>
                            <th class="py-3 px-4 text-left text-gray-600 font-medium">User ID</th>
                            <th class="py-3 px-4 text-left text-gray-600 font-medium">Start Time</th>
                            <th class="py-3 px-4 text-left text-gray-600 font-medium">End Time</th>
                            <th class="py-3 px-4 text-left text-gray-600 font-medium">Total Hours</th>
                            <th class="py-3 px-4 text-left text-gray-600 font-medium">Reason</th>
                            <th class="py-3 px-4 text-left text-gray-600 font-medium">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($requests as $request)
                            <tr class="border-b hover:bg-gray-50 transition-colors duration-200">
                                <td class="py-3 px-4">{{ $request->user->name }}</td>
                                <td class="py-3 px-4">{{ ucfirst($request->type) }}</td>
                                <td class="py-3 px-4">{{ $request->user_id }}</td>
                                <td class="py-3 px-4">{{ $request->start_time }}</td>
                                <td class="py-3 px-4">{{ $request->end_time }}</td>
                                <td class="py-3 px-4">{{ $request->total_hours }}</td>
                                <td class="py-3 px-4">{{ $request->reason }}</td>
                                <td class="py-3 px-4">
                                    <div class="flex space-x-2">
                                        <form action="{{ route('admin.requests.approve', $request->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition duration-200">
                                                Approve
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.requests.reject', $request->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition duration-200">
                                                Reject
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-layout>
