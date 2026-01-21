<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Aplikasi Mahasiswa</title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link 
      rel="stylesheet" 
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    />
</head>

<body class="bg-gray-100 min-h-screen">

    <!-- Header -->
    <header class="bg-white shadow mb-8">
        <div class="max-w-6xl mx-auto px-6 py-4 flex items-center gap-3">
            <i class="fas fa-book text-xl text-blue-600"></i>
            <h1 class="text-xl font-bold text-gray-800">
                Sistem Mahasiswa
            </h1>
        </div>
    </header>

    <!-- Content -->
    <main class="max-w-6xl mx-auto px-6">
        @yield('content')
    </main>

</body>
</html>
