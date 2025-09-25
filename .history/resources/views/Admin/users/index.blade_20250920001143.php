@extends('Admin.AdminLayouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-6">Users Management</h1>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full text-sm text-left text-gray-600">
            <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3">#</th>
                    <th class="px-6 py-3">Name</th>
                    <th class="px-6 py-3">Email</th>
                    <th class="px-6 py-3">Role</th>
                    <th class="px-6 py-3">Created At</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-6 py-3">{{ $loop->iteration }}</td>
                    <td class="px-6 py-3 font-semibold">{{ $u->name }}</td>
                    <td class="px-6 py-3">{{ $u->email }}</td>
                    <td class="px-6 py-3">
                        <span class="px-2 py-1 text-xs rounded-full 
                            {{ $u->role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                            {{ ucfirst($u->role) }}
                        </span>
                    </td>
                    <td class="px-6 py-3 text-gray-500">{{ $u->created_at->format('Y-m-d') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-400">
                        No users found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
