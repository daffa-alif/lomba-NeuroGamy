@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">

    <!-- Profil User -->
    <div class="flex items-center space-x-4 mb-8">
        <!-- Foto Profil -->
        <div class="w-16 h-16 rounded-full overflow-hidden bg-gray-200">
            @if($profileImage)
                <img src="{{ asset('storage/' . $profileImage) }}" alt="Profile"
                     class="w-full h-full object-cover">
            @else
                <img src="https://ui-avatars.com/api/?name={{ urlencode($username) }}&background=random"
                     alt="Profile" class="w-full h-full object-cover">
            @endif
        </div>

        <!-- Nama -->
        <div>
            <h2 class="text-xl font-bold">{{ $username }}</h2>
            <p class="text-gray-600 text-sm">Statistik bacaan & quiz</p>
        </div>
    </div>

    <!-- Statistik Ringkas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-4 rounded-lg shadow">
            <p class="text-gray-500">Jumlah Buku Dibaca</p>
            <h2 class="text-2xl font-bold">{{ $totalBooks }}</h2>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <p class="text-gray-500">Jumlah Quiz</p>
            <h2 class="text-2xl font-bold">{{ $totalQuizzes }}</h2>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <p class="text-gray-500">Rata-rata Skor</p>
            <h2 class="text-2xl font-bold">{{ $averageScore ?? '-' }}</h2>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <p class="text-gray-500">Skor Tertinggi</p>
            <h2 class="text-2xl font-bold">{{ $highestScore ?? '-' }}</h2>
        </div>
    </div>

    <!-- Riwayat Quiz -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl font-bold mb-4">Riwayat Quiz</h2>
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border px-4 py-2 text-left">Judul Buku</th>
                    <th class="border px-4 py-2">Halaman</th>
                    <th class="border px-4 py-2">Skor</th>
                    <th class="border px-4 py-2">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td class="border px-4 py-2">{{ $log->book->book_title ?? '-' }}</td>
                        <td class="border px-4 py-2 text-center">{{ $log->pages }}</td>
                        <td class="border px-4 py-2 text-center">{{ $log->score ?? '-' }}</td>
                        <td class="border px-4 py-2">{{ $log->created_at->format('d M Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center p-4 text-gray-500">Belum ada data quiz.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
