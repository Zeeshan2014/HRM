<x-layout>
    <div class="p-6 bg-gray-100">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Admin Dashboard</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Total Users -->
            

            <!-- User Dropdown to Select Total Hours -->
            <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">Select User</h2>
                <select id="userDropdown" class="block w-full p-3 border border-gray-300 rounded-md text-gray-700 focus:ring-2 focus:ring-indigo-500 transition">
                    <option value="">-- Select a user --</option>
                    @foreach ($usersWithAttendance as $user)
                        <option value="{{ $user->id }}" 
                                data-weekly-hours="{{ $user->weekly_hours }}" 
                                data-monthly-hours="{{ $user->monthly_hours }}" 
                                data-remaining-weekly-hours="{{ $user->remaining_weekly_hours }}" 
                                data-remaining-monthly-hours="{{ $user->remaining_monthly_hours }}">
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Display Weekly and Monthly Hours for Selected User -->
            <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                <h2 class="text-xl font-semibold text-gray-700 mb-2">User Hours</h2>
                <div class="mt-4">
                    <p class="text-lg"><strong>Weekly Hours:</strong> <span id="userWeeklyHours" class="text-indigo-600 font-medium">--</span> / 36 hours</p>
                    <p class="text-lg mt-2"><strong>Remaining Weekly Hours:</strong> <span id="userRemainingWeeklyHours" class="text-indigo-600 font-medium">--</span></p>
                </div>
                <div class="mt-4">
                    <p class="text-lg"><strong>Monthly Hours:</strong> <span id="userMonthlyHours" class="text-indigo-600 font-medium">--</span> / 144 hours</p>
                    <p class="text-lg mt-2"><strong>Remaining Monthly Hours:</strong> <span id="userRemainingMonthlyHours" class="text-indigo-600 font-medium">--</span></p>
                </div>
            </div>
        </div>
    </div>
    
    

    <!-- JavaScript to handle the user selection and format hours and minutes -->
    <script>
        document.getElementById('userDropdown').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const weeklyHours = parseFloat(selectedOption.getAttribute('data-weekly-hours'));
            const remainingWeeklyHours = parseFloat(selectedOption.getAttribute('data-remaining-weekly-hours'));
            const monthlyHours = parseFloat(selectedOption.getAttribute('data-monthly-hours'));
            const remainingMonthlyHours = parseFloat(selectedOption.getAttribute('data-remaining-monthly-hours'));

            // Convert hours to hours and minutes format
            const formatHoursMinutes = (totalHours) => {
                const hours = Math.floor(totalHours);
                const minutes = Math.round((totalHours - hours) * 60);
                return `${hours} hours ${minutes} minutes`;
            };

            document.getElementById('userWeeklyHours').textContent = weeklyHours ? formatHoursMinutes(weeklyHours) : '--';
            document.getElementById('userRemainingWeeklyHours').textContent = remainingWeeklyHours ? formatHoursMinutes(remainingWeeklyHours) : '--';
            document.getElementById('userMonthlyHours').textContent = monthlyHours ? formatHoursMinutes(monthlyHours) : '--';
            document.getElementById('userRemainingMonthlyHours').textContent = remainingMonthlyHours ? formatHoursMinutes(remainingMonthlyHours) : '--';
        });
    </script>
</x-layout>