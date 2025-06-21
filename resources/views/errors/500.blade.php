<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>500 - Kesalahan Server</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-screen w-screen bg-gray-100 flex items-center justify-center">

  <div class="container flex flex-col md:flex-row items-center justify-center px-5 text-gray-700">
    <div class="max-w-md">
      <div class="text-5xl font-bold">500</div>
      <p class="text-2xl md:text-3xl font-light leading-normal mt-2">
        Ada yang salah di sisi server.
      </p>
      <p class="mb-8 mt-4">
        Kami sedang berusaha memperbaikinya. Silakan coba beberapa saat lagi.
      </p>
      <a href="{{ url('/') }}" class="px-4 inline py-2 text-sm font-medium shadow text-white rounded-lg bg-blue-600 hover:bg-blue-700">
        Kembali ke Beranda
      </a>
    </div>
  </div>

</body>
</html>
