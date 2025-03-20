<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">

<head>
    <!-- 📌 Mengatur karakter encoding agar mendukung berbagai bahasa -->
    <meta charset="UTF-8">
    
    <!-- 📌 Mengatur viewport agar responsif di semua perangkat -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- 📌 Menambahkan CSRF token untuk keamanan dalam permintaan form -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- 📌 Menentukan judul halaman, jika tidak ada akan default ke "PondokBaca" -->
    <title>{{ $title ?? 'PondokBaca' }}</title>
    
    <!-- 📌 Menggunakan Vite untuk memuat file CSS dan JavaScript -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- 📌 Memuat gaya Livewire agar dapat digunakan dalam komponen -->
    @livewireStyles
</head>

<body class="bg-[#F8FAFC]">
    <!-- 📌 Tempat utama untuk menyisipkan konten dari halaman lain -->
    {{ $slot }}

    <!-- 📌 Memuat script Livewire agar dapat digunakan dalam halaman -->
    @livewireScripts

    <script>
        // 📌 Event listener untuk menangani notifikasi dengan SweetAlert
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('swal', (params) => {
                Swal.fire({
                    title: params.title, // 🔹 Judul notifikasi
                    text: params.text, // 🔹 Pesan dalam notifikasi
                    icon: params.icon, // 🔹 Jenis ikon (success, error, info, warning)
                    confirmButtonColor: '#1F4B3F', // 🔹 Warna tombol konfirmasi
                    confirmButtonText: 'OK' // 🔹 Teks tombol konfirmasi
                });
            });
        });
    </script>
</body>

</html>
