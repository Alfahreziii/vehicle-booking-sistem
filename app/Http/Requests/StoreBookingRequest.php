<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        return $user && $user->hasRole('admin');
    }

    public function rules(): array
    {
        return [
            'vehicle_id'       => ['required', 'exists:vehicles,id'],
            'driver_id'        => ['required', 'exists:drivers,id'],
            'purpose'          => ['required', 'string', 'max:255'],
            'description'      => ['nullable', 'string'],
            'destination'      => ['required', 'string', 'max:255'],
            'passenger_count'  => ['required', 'integer', 'min:1', 'max:50'],
            'departure_at'     => ['required', 'date', 'after:now'],
            'return_at'        => ['required', 'date', 'after:departure_at'],
            'approvers'        => ['required', 'array', 'min:2', 'max:5'],
            'approvers.*'      => ['required', 'exists:users,id', 'distinct'],
        ];
    }

    public function messages(): array
    {
        return [
            'vehicle_id.required'      => 'Kendaraan wajib dipilih.',
            'driver_id.required'       => 'Driver wajib dipilih.',
            'purpose.required'         => 'Tujuan pemesanan wajib diisi.',
            'destination.required'     => 'Destinasi wajib diisi.',
            'departure_at.required'    => 'Tanggal berangkat wajib diisi.',
            'departure_at.after'       => 'Tanggal berangkat harus setelah waktu sekarang.',
            'return_at.after'          => 'Tanggal kembali harus setelah tanggal berangkat.',
            'approvers.required'       => 'Minimal 2 approver wajib dipilih.',
            'approvers.min'            => 'Minimal 2 level persetujuan.',
            'approvers.*.distinct'     => 'Approver tidak boleh sama.',
        ];
    }
}
