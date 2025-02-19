<div>
    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-gray-400">Total Users</h3>
                <span class="p-2 bg-emerald-50 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </span>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $totalUsers }}</p>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-gray-400">Total Books</h3>
                <span class="p-2 bg-blue-50 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </span>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $totalBooks }}</p>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-gray-400">Total Peminjaman</h3>
                <span class="p-2 bg-purple-50 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </span>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $totalLoans }}</p>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-gray-400">Peminjaman Aktif</h3>
                <span class="p-2 bg-yellow-50 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $activeLoans }}</p>
        </div>
    </div>

    <!-- Recent Loans -->
    <div class="bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-xl font-semibold text-gray-800">Peminjaman Terbaru</h2>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Book</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentLoans as $loan)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg overflow-hidden">
                                        <img src="{{ $loan->user->profile_img ?? 'https://ui-avatars.com/api/?name='.$loan->user->name }}" 
                                             alt="{{ $loan->user->name }}" 
                                             class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <p class="font-medium text-sm">{{ $loan->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $loan->user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $loan->buku->judul }}</td>
                            <td>
                                <span class="badge {{ 
                                    match($loan->status) {
                                        'pending' => 'badge-warning',
                                        'diproses' => 'badge-info',
                                        'dikirim' => 'badge-primary',
                                        'dipinjam' => 'badge-success',
                                        'terlambat' => 'badge-error',
                                        'dikembalikan' => 'badge-neutral',
                                        'ditolak' => 'badge-error',
                                        default => 'badge-ghost'
                                    }
                                }}">
                                    {{ $loan->status }}
                                </span>
                            </td>
                            <td>{{ $loan->created_at->format('d M Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div> 