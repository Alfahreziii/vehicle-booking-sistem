{{-- resources/views/approver/approvals/show.blade.php --}}

@extends('layouts.app')

@section('title', 'Detail Persetujuan')
@section('page-title', 'Detail Persetujuan')

@section('content')
    <div class="max-w-4xl mx-auto space-y-5">

        {{-- Back --}}
        <a href="{{ route('approvals.index') }}"
            class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-700">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali ke daftar
        </a>

        {{-- Header booking --}}
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <span class="font-mono text-xs text-slate-400">{{ $booking->booking_code }}</span>
                    <h2 class="mt-1 text-lg font-semibold text-slate-800">{{ $booking->purpose }}</h2>
                    <p class="mt-0.5 text-sm text-slate-500">Diajukan oleh <strong>{{ $booking->requester->name }}</strong>
                        · {{ $booking->requester->department?->name }}
                        · {{ $booking->requester->region?->name }}
                    </p>
                </div>
                <div>{!! $booking->status_badge !!}</div>
            </div>
        </div>

        <div class="grid gap-5 lg:grid-cols-3">

            {{-- Info Perjalanan --}}
            <div class="lg:col-span-2 space-y-5">

                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <h3 class="mb-4 text-sm font-semibold text-slate-700 flex items-center gap-2">
                        <svg class="h-4 w-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                        </svg>
                        Informasi Perjalanan
                    </h3>
                    <dl class="grid grid-cols-2 gap-x-6 gap-y-4 text-sm">
                        <div>
                            <dt class="text-xs font-medium text-slate-400 uppercase tracking-wide">Destinasi</dt>
                            <dd class="mt-1 font-medium text-slate-700">{{ $booking->destination }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-slate-400 uppercase tracking-wide">Jumlah Penumpang</dt>
                            <dd class="mt-1 font-medium text-slate-700">{{ $booking->passenger_count }} orang</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-slate-400 uppercase tracking-wide">Tanggal Berangkat</dt>
                            <dd class="mt-1 font-medium text-slate-700">{{ $booking->departure_at->format('d M Y, H:i') }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-slate-400 uppercase tracking-wide">Estimasi Kembali</dt>
                            <dd class="mt-1 font-medium text-slate-700">{{ $booking->return_at->format('d M Y, H:i') }}</dd>
                        </div>
                        @if ($booking->description)
                            <div class="col-span-2">
                                <dt class="text-xs font-medium text-slate-400 uppercase tracking-wide">Keterangan</dt>
                                <dd class="mt-1 text-slate-600">{{ $booking->description }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>

                {{-- Kendaraan & Driver --}}
                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <h3 class="mb-4 text-sm font-semibold text-slate-700 flex items-center gap-2">
                        <svg class="h-4 w-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 17a2 2 0 100-4 2 2 0 000 4zm8 0a2 2 0 100-4 2 2 0 000 4zM3 5h2l2 7h9l2-6H6" />
                        </svg>
                        Kendaraan & Driver
                    </h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="rounded-lg bg-slate-50 p-3">
                            <p class="text-xs text-slate-400 font-medium uppercase tracking-wide mb-1.5">Kendaraan</p>
                            <p class="font-semibold text-slate-800 text-sm">{{ $booking->vehicle->brand }}
                                {{ $booking->vehicle->model }}</p>
                            <p class="text-xs text-slate-500 mt-0.5">{{ $booking->vehicle->plate_number }} ·
                                {{ $booking->vehicle->type_label }}</p>
                            <p class="text-xs text-slate-400 mt-0.5">{{ $booking->vehicle->region->name }}</p>
                        </div>
                        <div class="rounded-lg bg-slate-50 p-3">
                            <p class="text-xs text-slate-400 font-medium uppercase tracking-wide mb-1.5">Driver</p>
                            <p class="font-semibold text-slate-800 text-sm">{{ $booking->driver->user->name }}</p>
                            <p class="text-xs text-slate-500 mt-0.5">SIM {{ $booking->driver->license_type }} ·
                                {{ $booking->driver->license_number }}</p>
                            <p class="text-xs text-slate-400 mt-0.5">Exp:
                                {{ $booking->driver->license_expiry->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan --}}
            <div class="space-y-5">

                {{-- Approval Chain --}}
                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <h3 class="mb-4 text-sm font-semibold text-slate-700">Rantai Persetujuan</h3>
                    <div class="space-y-3">
                        @foreach ($booking->approvals as $approval)
                            <div class="flex items-start gap-3">
                                {{-- Level indicator --}}
                                <div class="flex flex-col items-center">
                                    <div
                                        class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-xs font-bold
                                {{ $approval->status === 'approved'
                                    ? 'bg-emerald-100 text-emerald-700'
                                    : ($approval->status === 'rejected'
                                        ? 'bg-red-100 text-red-700'
                                        : ($booking->current_approval_level + 1 === $approval->level
                                            ? 'bg-amber-100 text-amber-700 ring-2 ring-amber-400'
                                            : 'bg-slate-100 text-slate-400')) }}">
                                        {{ $approval->level }}
                                    </div>
                                    @if (!$loop->last)
                                        <div class="mt-1 h-5 w-px bg-slate-200"></div>
                                    @endif
                                </div>
                                {{-- Info --}}
                                <div class="flex-1 pb-2">
                                    <p class="text-sm font-medium text-slate-700">{{ $approval->approver->name }}</p>
                                    <p class="text-xs text-slate-400">Level {{ $approval->level }}</p>
                                    @if ($approval->acted_at)
                                        <p class="text-xs text-slate-400 mt-0.5">
                                            {{ $approval->acted_at->format('d M Y, H:i') }}</p>
                                    @endif
                                    @if ($approval->notes)
                                        <p class="mt-1 text-xs text-slate-600 italic">"{{ $approval->notes }}"</p>
                                    @endif
                                </div>
                                {{-- Status icon --}}
                                <div class="shrink-0 mt-1">
                                    @if ($approval->status === 'approved')
                                        <svg class="h-4 w-4 text-emerald-500" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    @elseif($approval->status === 'rejected')
                                        <svg class="h-4 w-4 text-red-500" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    @elseif($booking->current_approval_level + 1 === $approval->level)
                                        <div
                                            class="h-4 w-4 rounded-full border-2 border-amber-400 bg-amber-100 animate-pulse">
                                        </div>
                                    @else
                                        <div class="h-4 w-4 rounded-full border-2 border-slate-200 bg-slate-50"></div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Form Aksi (hanya jika bisa diproses) --}}
                @if ($canProcess)
                    <div class="rounded-xl border border-blue-200 bg-blue-50 p-5 shadow-sm" x-data="{ action: '' }">
                        <h3 class="mb-3 text-sm font-semibold text-slate-700">Keputusan Anda</h3>

                        <form action="{{ route('approvals.process', $booking) }}" method="POST" class="space-y-3">
                            @csrf

                            {{-- Pilihan aksi --}}
                            <div class="grid grid-cols-2 gap-2">
                                <label class="cursor-pointer">
                                    <input type="radio" name="action" value="approve" class="peer sr-only"
                                        x-model="action">
                                    <div
                                        class="flex items-center justify-center gap-1.5 rounded-lg border-2 border-slate-200 bg-white px-3 py-2.5 text-sm font-medium text-slate-600 transition-all
                                        peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:text-emerald-700">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Setujui
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="action" value="reject" class="peer sr-only"
                                        x-model="action">
                                    <div
                                        class="flex items-center justify-center gap-1.5 rounded-lg border-2 border-slate-200 bg-white px-3 py-2.5 text-sm font-medium text-slate-600 transition-all
                                        peer-checked:border-red-500 peer-checked:bg-red-50 peer-checked:text-red-700">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Tolak
                                    </div>
                                </label>
                            </div>

                            {{-- Catatan --}}
                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1">
                                    Catatan
                                    <span x-show="action === 'reject'" class="text-red-500">*</span>
                                    <span x-show="action !== 'reject'" class="text-slate-400">(opsional)</span>
                                </label>
                                <textarea name="notes" rows="3"
                                    class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 placeholder:text-slate-400 focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100 @error('notes') border-red-400 @enderror"
                                    :placeholder="action === 'reject' ? 'Wajib: tuliskan alasan penolakan...' :
                                        'Tambahkan catatan jika diperlukan...'"
                                    :required="action === 'reject'">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Submit --}}
                            <button type="submit" :disabled="action === ''"
                                :class="action === 'approve'
                                    ?
                                    'bg-emerald-600 hover:bg-emerald-700 text-white' :
                                    (action === 'reject' ?
                                        'bg-red-600 hover:bg-red-700 text-white' :
                                        'bg-slate-200 text-slate-400 cursor-not-allowed')"
                                class="w-full rounded-lg px-4 py-2.5 text-sm font-semibold transition-colors">
                                <span x-show="action === 'approve'">Konfirmasi Persetujuan</span>
                                <span x-show="action === 'reject'">Konfirmasi Penolakan</span>
                                <span x-show="action === ''">Pilih keputusan terlebih dahulu</span>
                            </button>
                        </form>
                    </div>
                @else
                    {{-- Sudah diproses atau bukan giliran --}}
                    @if ($myApproval->status !== 'waiting')
                        <div
                            class="rounded-xl border {{ $myApproval->status === 'approved' ? 'border-emerald-200 bg-emerald-50' : 'border-red-200 bg-red-50' }} p-4">
                            <p
                                class="text-sm font-semibold {{ $myApproval->status === 'approved' ? 'text-emerald-700' : 'text-red-700' }}">
                                Anda sudah {{ $myApproval->status === 'approved' ? 'menyetujui' : 'menolak' }} booking ini
                                @if ($myApproval->acted_at)
                                    pada {{ $myApproval->acted_at->format('d M Y, H:i') }}
                                @endif
                            </p>
                            @if ($myApproval->notes)
                                <p
                                    class="mt-1 text-xs {{ $myApproval->status === 'approved' ? 'text-emerald-600' : 'text-red-600' }}">
                                    "{{ $myApproval->notes }}"
                                </p>
                            @endif
                        </div>
                    @endif
                @endif

            </div>
        </div>
    </div>
@endsection
