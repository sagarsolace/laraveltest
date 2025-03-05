<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Livewire Invoices</title>
    
    @vite('resources/css/app.css') <!-- If using Vite -->
    @livewireStyles <!-- Required for Livewire -->
</head>
<body>
    
    <div class="container mx-auto p-6">
        @yield('content')
    </div>

    @livewireScripts<!-- Required for Livewire -->
    

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
