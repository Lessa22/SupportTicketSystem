<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $rules = [
            'title'=>'required|max:255',
            'description'=>'required|min:10',
            'category_id'=>'required|exists:categories,id',
            'priority_id'=>'required|exists:priorities,id',
        ];

        if (auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isSupervisor())) {
            $rules['agent_id'] = 'nullable|exists:users,id,role,agent';
        }

        return $rules;
    }
}