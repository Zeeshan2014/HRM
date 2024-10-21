<x-layout>

    <div class="text-center text-gray-600">
        <!-- Add your main content here -->

        


        <div class="mt-6 border border-gray-200 shadow-lg rounded-xl p-8 bg-white max-w-lg mx-auto">
            <h1 class="text-3xl font-semibold mb-8 text-gray-800">
                Work From Home
            </h1>

            <div class="space-y-6">

                <!-- Clock In Button -->
                <button type="button"
                    class="text-white bg-green-600 hover:bg-green-500 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-base px-6 py-3  transition duration-300 ease-in-out">
                    Clock In
                </button>

                <!-- Clock Out Button -->
                <button type="button"
                    class="text-white bg-red-600 hover:bg-red-500 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-base px-6 py-3  transition duration-300 ease-in-out">
                    Clock Out
                </button>

                <!-- Request Button -->
                <button type="button"
                    class="text-gray-900 bg-white border border-gray-800 hover:bg-gray-700 hover:text-white focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-base px-6 py-3  transition duration-300 ease-in-out">
                    Request
                </button>

                <!-- Description Input Section -->
                <div>
                    <input type="text" placeholder="Enter details"
                        class="w-full p-4 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-base focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400 transition duration-300 ease-in-out" />
                    <button
                        class="text-white bg-blue-600 hover:bg-blue-500 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-base px-6 py-3 mt-4  transition duration-300 ease-in-out">
                        Submit
                    </button>
                </div>

            </div>
        </div>

    </div>


    </x-layout>
