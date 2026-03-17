<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateApprovalRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = Auth::user();
        return $user && method_exists($user, 'hasRole') && $user->hasRole(['approver', 'admin']);
    }

    public function rules(): array
    {
        return [
            'action' => ['required', 'in:approve,reject'],
            'notes'  => [
                'nullable',
                'string',
                'max:500',
                // Wajib diisi jika menolak
                $this->input('action') === 'reject' ? 'required' : 'nullable',
                'min:' . ($this->input('action') === 'reject' ? '10' : '0'),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'action.required' => 'Aksi wajib dipilih.',
            'action.in'       => 'Aksi tidak valid.',
            'notes.required'  => 'Alasan penolakan wajib diisi.',
            'notes.min'       => 'Alasan penolakan minimal 10 karakter.',
        ];
    }
}
