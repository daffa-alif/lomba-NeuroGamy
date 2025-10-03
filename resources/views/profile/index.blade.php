@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="container mx-auto p-6 space-y-12">

    <!-- Profile Header -->
    <div class="flex flex-col md:flex-row items-center justify-between">
        <div class="flex items-center space-x-6">
            <!-- Profile Picture -->
            <div class="w-24 h-24 rounded-full overflow-hidden shadow-lg border-4 border-white">
                @if($profileImage)
                    <img src="{{ asset('storage/' . $profileImage) }}" alt="Profile"
                         class="w-full h-full object-cover">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($username) }}&background=random&color=fff&size=128"
                         alt="Profile" class="w-full h-full object-cover">
                @endif
            </div>

            <!-- Name and Subtitle -->
            <div>
                <h1 class="text-4xl font-bold text-gray-800">{{ $username }}</h1>
                <p class="text-gray-600 text-lg">Statistik bacaan & quiz</p>
            </div>
        </div>

        <!-- Edit Button -->
        <a href="{{ route('profile.edit') }}"
           class="mt-4 md:mt-0 bg-black text-white py-2 px-6 rounded-md hover:bg-gray-800 transition font-semibold">
            Edit Profil
        </a>
    </div>

    <!-- Summary Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-xl shadow-2xl border">
            <p class="text-gray-500 font-semibold">Jumlah Buku Dibaca</p>
            <h3 class="text-4xl font-bold mt-2">{{ $totalBooks }}</h3>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-2xl border">
            <p class="text-gray-500 font-semibold">Jumlah Quiz</p>
            <h3 class="text-4xl font-bold mt-2">{{ $totalQuizzes }}</h3>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-2xl border">
            <p class="text-gray-500 font-semibold">Rata-rata Skor</p>
            <h3 class="text-4xl font-bold mt-2">{{ $averageScore ?? '-' }}</h3>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-2xl border">
            <p class="text-gray-500 font-semibold">Skor Tertinggi</p>
            <h3 class="text-4xl font-bold mt-2">{{ $highestScore ?? '-' }}</h3>
        </div>
    </div>

    <!-- Quiz History Table Card -->
     <div class="bg-white p-8 rounded-xl shadow-2xl w-full border">
        <h2 class="text-2xl font-bold mb-6">Riwayat Quiz</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-100 text-gray-600 uppercase text-xs font-semibold">
                    <tr>
                        <th class="p-4">Judul Buku</th>
                        <th class="p-4 text-center">Halaman</th>
                        <th class="p-4 text-center">Skor</th>
                        <th class="p-4">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @forelse($logs as $log)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-4 font-bold text-gray-800">{{ $log->book->book_title ?? '-' }}</td>
                            <td class="p-4 text-center">{{ $log->pages }}</td>
                            <td class="p-4 text-center font-bold">{{ $log->score ?? '-' }}</td>
                            <td class="p-4">{{ $log->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center p-8 text-gray-500">Belum ada data quiz.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

