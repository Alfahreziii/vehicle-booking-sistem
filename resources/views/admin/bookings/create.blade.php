@extends('layouts.app')

@section('title', 'Buat Pemesanan')
@section('page-title', 'Buat Pemesanan Kendaraan')

@section('content')
    <div class="max-w-7xl mx-auto">

        <div class="mb-5">
            <h2 class="text-xl font-semibold text-slate-800">Buat Pemesanan Kendaraan</h2>
            <p class="text-sm text-slate-500 mt-0.5">Isi form di bawah untuk membuat pemesanan baru</p>
        </div>

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4">
                <p class="text-sm font-semibold text-red-700 mb-2">Terdapat kesalahan pada form:</p>
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li class="text-sm text-red-600">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.bookings.store') }}" method="POST">
            @csrf
            <div class="flex gap-5 items-start flex-col lg:flex-row">

                {{-- ── Kolom Kiri ── --}}
                <div class="flex-1 space-y-5 w-full">

                    {{-- Info Perjalanan --}}
                    <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                        <div class="flex items-center gap-2 border-b border-slate-100 bg-slate-50 px-5 py-3.5">
                            <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-100">
                                <svg class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <h3 class="text-sm font-semibold text-slate-700">Informasi Perjalanan</h3>
                        </div>
                        <div class="p-5 space-y-4">

                            {{-- Tujuan --}}
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                                    Tujuan Pemesanan <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="purpose" value="{{ old('purpose') }}"
                                    placeholder="Contoh: Kunjungan klien ke kantor mitra"
                                    class="w-full rounded-lg border px-3 py-2.5 text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-100
                                          {{ $errors->has('purpose') ? 'border-red-400 bg-red-50 focus:border-red-400' : 'border-slate-200 focus:border-blue-400' }}">
                                @error('purpose')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Destinasi --}}
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                                    Destinasi <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="destination" value="{{ old('destination') }}"
                                    placeholder="Contoh: Gedung Midplaza, Jakarta Pusat"
                                    class="w-full rounded-lg border px-3 py-2.5 text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-100
                                          {{ $errors->has('destination') ? 'border-red-400 bg-red-50 focus:border-red-400' : 'border-slate-200 focus:border-blue-400' }}">
                                @error('destination')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Keterangan --}}
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                                    Keterangan Tambahan
                                    <span class="font-normal text-slate-400">(opsional)</span>
                                </label>
                                <textarea name="description" rows="3" placeholder="Informasi tambahan yang perlu diketahui approver..."
                                    class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm text-slate-700 placeholder:text-slate-400 focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100 resize-none">{{ old('description') }}</textarea>
                            </div>

                            {{-- Tanggal --}}
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                                        Tanggal Berangkat <span class="text-red-500">*</span>
                                    </label>
                                    <input type="datetime-local" name="departure_at" value="{{ old('departure_at') }}"
                                        min="{{ now()->addHour()->format('Y-m-d\TH:i') }}"
                                        class="w-full rounded-lg border px-3 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-100
                                              {{ $errors->has('departure_at') ? 'border-red-400 bg-red-50' : 'border-slate-200 focus:border-blue-400' }}">
                                    @error('departure_at')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                                        Estimasi Kembali <span class="text-red-500">*</span>
                                    </label>
                                    <input type="datetime-local" name="return_at" value="{{ old('return_at') }}"
                                        class="w-full rounded-lg border px-3 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-100
                                              {{ $errors->has('return_at') ? 'border-red-400 bg-red-50' : 'border-slate-200 focus:border-blue-400' }}">
                                    @error('return_at')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Jumlah penumpang --}}
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                                    Jumlah Penumpang <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="passenger_count" value="{{ old('passenger_count', 1) }}"
                                    min="1" max="50"
                                    class="w-32 rounded-lg border px-3 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-100
                                          {{ $errors->has('passenger_count') ? 'border-red-400 bg-red-50' : 'border-slate-200 focus:border-blue-400' }}">
                                @error('passenger_count')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>
                    </div>

                    {{-- Kendaraan & Driver --}}
                    <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                        <div class="flex items-center gap-2 border-b border-slate-100 bg-slate-50 px-5 py-3.5">
                            <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-amber-100">
                                <svg class="h-4 w-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8 17a2 2 0 100-4 2 2 0 000 4zm8 0a2 2 0 100-4 2 2 0 000 4zM3 5h2l2 7h9l2-6H6" />
                                </svg>
                            </div>
                            <h3 class="text-sm font-semibold text-slate-700">Kendaraan & Driver</h3>
                        </div>
                        <div class="p-5 space-y-4">

                            {{-- Kendaraan --}}
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                                    Pilih Kendaraan <span class="text-red-500">*</span>
                                </label>
                                <select name="vehicle_id"
                                    class="w-full rounded-lg border px-3 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-100
                                           {{ $errors->has('vehicle_id') ? 'border-red-400 bg-red-50' : 'border-slate-200 focus:border-blue-400' }}">
                                    <option value="">-- Pilih Kendaraan --</option>
                                    @foreach ($vehicles as $vehicle)
                                        <option value="{{ $vehicle->id }}" @selected(old('vehicle_id') == $vehicle->id)>
                                            {{ $vehicle->brand }} {{ $vehicle->model }} —
                                            {{ $vehicle->plate_number }} ({{ $vehicle->type_label }}) —
                                            {{ $vehicle->region->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('vehicle_id')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Driver --}}
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                                    Pilih Driver <span class="text-red-500">*</span>
                                </label>
                                <select name="driver_id"
                                    class="w-full rounded-lg border px-3 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-100
                                           {{ $errors->has('driver_id') ? 'border-red-400 bg-red-50' : 'border-slate-200 focus:border-blue-400' }}">
                                    <option value="">-- Pilih Driver --</option>
                                    @foreach ($drivers as $driver)
                                        <option value="{{ $driver->id }}" @selected(old('driver_id') == $driver->id)>
                                            {{ $driver->user->name }} —
                                            SIM {{ $driver->license_type }}
                                            (exp: {{ $driver->license_expiry->format('d M Y') }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('driver_id')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>

                {{-- ── Kolom Kanan — Approver ── --}}
                <div class="w-full lg:w-80 lg:sticky lg:top-20">
                    <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                        <div class="flex items-center gap-2 border-b border-slate-100 bg-slate-50 px-5 py-3.5">
                            <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-100">
                                <svg class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-slate-700">Rantai Persetujuan</h3>
                                <p class="text-xs text-slate-400">Pilih minimal 2 approver berurutan</p>
                            </div>
                        </div>

                        <div class="p-5 space-y-4">

                            @error('approvers')
                                <div class="rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs text-red-600">
                                    {{ $message }}
                                </div>
                            @enderror

                            {{-- Approver chain --}}
                            <div id="approver-chain" class="space-y-3">
                                @foreach (old('approvers', ['', '']) as $i => $val)
                                    <div class="approver-item">
                                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                                            Level {{ $i + 1 }}
                                            @if ($i === 0)
                                                <span class="text-red-500">*</span>
                                            @endif
                                        </label>
                                        <div class="flex gap-2">
                                            <span
                                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-600 text-xs font-bold text-white">
                                                L{{ $i + 1 }}
                                            </span>
                                            <select name="approvers[]"
                                                class="flex-1 rounded-lg border border-slate-200 px-2.5 py-2 text-sm text-slate-700 focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                                                <option value="">-- Pilih --</option>
                                                @foreach ($approvers as $approver)
                                                    <option value="{{ $approver->id }}" @selected($val == $approver->id)>
                                                        {{ $approver->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @if ($i >= 2)
                                                <button type="button"
                                                    class="btn-remove-approver flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-red-200 text-red-500 hover:bg-red-50 transition-colors">
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Tambah level --}}
                            <button type="button" id="btn-add-approver"
                                class="w-full rounded-lg border border-dashed border-slate-300 py-2 text-xs font-medium text-slate-500 hover:border-blue-400 hover:text-blue-600 transition-colors">
                                + Tambah Level Persetujuan
                            </button>

                            <div class="border-t border-slate-100 pt-4 space-y-2">
                                <button type="submit"
                                    class="w-full rounded-lg bg-blue-600 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 transition-colors flex items-center justify-center gap-2">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                    </svg>
                                    Kirim Pemesanan
                                </button>
                                <a href="{{ route('admin.bookings.index') }}"
                                    class="block w-full rounded-lg border border-slate-200 py-2.5 text-center text-sm font-medium text-slate-600 hover:bg-slate-50 transition-colors">
                                    Batal
                                </a>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            let levelCount = {{ count(old('approvers', ['', ''])) }};
            const maxLevel = 5;
            const approvers = @json($approvers->map(fn($a) => ['id' => $a->id, 'name' => $a->name]));

            const chain = document.getElementById('approver-chain');
            const btnAdd = document.getElementById('btn-add-approver');

            // Attach remove listener ke tombol yang sudah ada (level 3+)
            chain.querySelectorAll('.btn-remove-approver').forEach(btn => {
                btn.addEventListener('click', removeApprover);
            });

            btnAdd.addEventListener('click', function() {
                if (levelCount >= maxLevel) {
                    alert('Maksimal 5 level persetujuan.');
                    return;
                }
                levelCount++;

                let options = '<option value="">-- Pilih --</option>';
                approvers.forEach(a => {
                    options += `<option value="${a.id}">${a.name}</option>`;
                });

                const item = document.createElement('div');
                item.className = 'approver-item';
                item.innerHTML = `
            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Level ${levelCount}</label>
            <div class="flex gap-2">
                <span class="level-badge flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-600 text-xs font-bold text-white">
                    L${levelCount}
                </span>
                <select name="approvers[]"
                        class="flex-1 rounded-lg border border-slate-200 px-2.5 py-2 text-sm text-slate-700 focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                    ${options}
                </select>
                <button type="button"
                        class="btn-remove-approver flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-red-200 text-red-500 hover:bg-red-50 transition-colors">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>`;

                chain.appendChild(item);
                item.querySelector('.btn-remove-approver').addEventListener('click', removeApprover);
                updateAddButton();
                relabelAll();
            });

            function removeApprover() {
                this.closest('.approver-item').remove();
                levelCount--;
                relabelAll();
                updateAddButton();
            }

            function relabelAll() {
                chain.querySelectorAll('.approver-item').forEach((item, i) => {
                    const label = item.querySelector('label');
                    const badge = item.querySelector('.level-badge');
                    if (label) label.textContent = `Level ${i + 1}`;
                    if (badge) badge.textContent = `L${i + 1}`;
                });
            }

            function updateAddButton() {
                btnAdd.disabled = levelCount >= maxLevel;
                btnAdd.classList.toggle('opacity-50', levelCount >= maxLevel);
                btnAdd.classList.toggle('cursor-not-allowed', levelCount >= maxLevel);
            }
        </script>
    @endpush

@endsection
