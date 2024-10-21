<x-layout>
    <div class="container mx-auto p-8">
        <h1 class="text-3xl font-bold mb-6 text-gray-900">Request History</h1>

        <div class="overflow-x-auto">
            <div class="inline-block min-w-full shadow-lg rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-300 bg-white">
                    <thead class="bg-gray-900 text-white">
                        <tr>
                            <th class="py-3 px-6 text-left text-xs font-semibold uppercase tracking-wider">ID</th>
                            <th class="py-3 px-6 text-left text-xs font-semibold uppercase tracking-wider">User Name</th>
                            <th class="py-3 px-6 text-left text-xs font-semibold uppercase tracking-wider">Type</th>
                            <th class="py-3 px-6 text-left text-xs font-semibold uppercase tracking-wider">Start Time</th>
                            <th class="py-3 px-6 text-left text-xs font-semibold uppercase tracking-wider">End Time</th>
                            <th class="py-3 px-6 text-left text-xs font-semibold uppercase tracking-wider">Reason</th>
                            
                            <th class="py-3 px-6 text-left text-xs font-semibold uppercase tracking-wider">Total Hours</th>
                            <th class="py-3 px-6 text-left text-xs font-semibold uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 text-gray-800">
                        @foreach($requests as $request)
                            <tr class="hover:bg-gray-50 transition duration-200 ease-in-out">
                                <td class="py-4 px-6 text-sm">{{ $request->id }}</td>
                                <td class="py-4 px-6 text-sm">{{ $request->user->name }}</td>
                                <td class="py-4 px-6 text-sm">{{ ucfirst($request->type) }}</td>
                                <td class="py-4 px-6 text-sm">{{ \Carbon\Carbon::parse($request->start_time)->format('d M Y, h:i A') }}</td>
                                <td class="py-4 px-6 text-sm">{{ \Carbon\Carbon::parse($request->end_time)->format('d M Y, h:i A') }}</td>
                                <td class="py-4 px-6 text-sm">{{ $request->reason }}</td>
                                
                                <td class="py-4 px-6 text-sm">{{ $request->total_hours }}</td>
                                <td class="py-4 px-6 text-sm">
                                    <span class="inline-block px-2 py-1 font-semibold text-xs rounded-full
                                        {{ $request->status == 'approved' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
</x-layout>
