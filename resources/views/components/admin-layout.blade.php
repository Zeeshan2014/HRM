<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>HRM</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    function toggleClockElements() {
      const clockElements = document.getElementById('clock-elements');
      clockElements.classList.toggle('hidden');
    }
  </script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <style>
    .sidebar-custom {
      border-radius: 40px;
    }
  </style>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#E0E6E9]">

  <div class="flex flex-col md:flex-row">
    <!-- Sidebar -->
    <aside class="w-full md:w-48 bg-white p-4 flex flex-col sidebar-custom ml-0 md:ml-4 mt-4 mb-4">
      <div class="flex flex-col items-center justify-center mb-8">
        <!-- Logo -->
        <div class="text-2xl font-bold"><img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-auto"></div>
      </div>

      <!-- Menu Items -->
      <nav class="space-y-4">
        <a href="/dashboard" class="flex items-center space-x-2 p-3 hover:bg-black hover:text-white rounded-lg">
          <span class="material-icons">dashboard</span>
          <span>Dashboard</span>
        </a>
       
        <a href="{{ route('admin.requests') }}" class="flex items-center space-x-2 p-3 hover:bg-black hover:text-white rounded-lg">
          <span class="material-icons">sick</span>
          <span>Requests</span>
        </a>
      </nav>

      <!-- User Profile -->
      <div class="mt-auto flex items-center space-x-4"></div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-6">
      <!-- Top Bar -->
      <div class="flex justify-between items-center mb-8">
        <!-- Search Bar -->
        <div class="relative w-full md:w-1/4">
          <input type="text" placeholder="Type searching..."
            class="w-full p-3 pl-10 rounded-full bg-white border border-gray-300">
          <span class="absolute left-3 top-1/2 transform -translate-y-1/2 material-icons text-gray-400">search</span>
        </div>

        <!-- Utility Icons -->
        <div class="hidden sm:flex sm:items-center sm:ms-6">
          <x-dropdown align="right" width="48">
            <x-slot name="trigger">
              <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                <div>{{ Auth::user()->name }}</div>
                <div class="ms-1">
                  <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                  </svg>
                </div>
              </button>
            </x-slot>

            <x-slot name="content">
              <x-dropdown-link :href="route('profile.edit')">
                {{ __('Profile') }}
              </x-dropdown-link>

              <!-- Authentication -->
              <form method="POST" action="{{ route('logout') }}">
                @csrf

                <x-dropdown-link :href="route('logout')"
                  onclick="event.preventDefault();
                                this.closest('form').submit();">
                  {{ __('Log Out') }}
                </x-dropdown-link>
              </form>
            </x-slot>
          </x-dropdown>
        </div>
      </div>

      <!-- Main Section -->
      {{ $slot }}

    </main>
  </div>

</body>

</html>
