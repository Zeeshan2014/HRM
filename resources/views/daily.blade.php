<x-layout>


<div class="text-center text-gray-600">
        <!-- Add your main content here -->

        <div class="border border-gray-200 shadow-lg rounded-xl p-8 bg-white max-w-lg mx-auto">
            <h1 class="text-3xl font-semibold mb-8 text-gray-800">Daily Attendance</h1>

            <!-- Clock In Button -->
            @if (session('success'))
            <div class="text-green-500 mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="/daily">
            @csrf
            <button type="submit"
                class="text-white bg-green-600 hover:bg-green-500 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-base px-6 py-3 mb-4 transition duration-300 ease-in-out"
                @if ($isClockedIn) disabled @endif>
                Clock In
            </button>
        </form>

        <!-- Clock Out Button -->
        <form method="POST" action="/daily">
            @csrf
            <button type="submit"
                class="text-white bg-red-600 hover:bg-red-500 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-base px-6 py-3 mb-4 transition duration-300 ease-in-out"
                @if (!$isClockedIn) disabled @endif>
                Clock Out
            </button>
        </form>

            
            
        </div>





    </div>


</x-layout>
