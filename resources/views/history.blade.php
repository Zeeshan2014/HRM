<x-layout>
    <div class="container mx-auto p-8">
        <h2 class="text-3xl font-semibold text-gray-900 mb-6">Request History</h2>

        @if($requests->isEmpty())
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded-md" role="alert">
                <p class="font-bold">No Requests Found</p>
                <p>You have no approved, rejected, or pending requests at the moment.</p>
            </div>
        @else
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-900 text-white">
                        <tr>
                            <th class="py-3 px-6 text-left text-xs font-semibold uppercase tracking-wider">Request Type</th>
                            <th class="py-3 px-6 text-left text-xs font-semibold uppercase tracking-wider">Start Time</th>
                            <th class="py-3 px-6 text-left text-xs font-semibold uppercase tracking-wider">End Time</th>
                            <th class="py-3 px-6 text-left text-xs font-semibold uppercase tracking-wider">Total Hours</th>
                            <th class="py-3 px-6 text-left text-xs font-semibold uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 text-gray-800">
                        @foreach($requests as $request)
                            <tr class="hover:bg-gray-50 transition duration-200 ease-in-out">
                                <td class="py-4 px-6 text-sm">{{ ucfirst($request->type) }}</td>
                                <td class="py-4 px-6 text-sm">{{ \Carbon\Carbon::parse($request->start_time)->format('d M Y, h:i A') }}</td>
                                <td class="py-4 px-6 text-sm">{{ \Carbon\Carbon::parse($request->end_time)->format('d M Y, h:i A') }}</td>
                                <td class="py-4 px-6 text-sm">{{ $request->total_hours }} hours</td>
                                <td class="py-4 px-6 text-sm">
                                    <span class="inline-block px-2 py-1 font-semibold text-xs rounded-full
                                        {{ $request->status == 'approved' ? 'bg-green-100 text-green-700' : 
                                           ($request->status == 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-layout>
