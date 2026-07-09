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
        return [

            'title'=>'required|max:255',

            'description'=>'required|min:10',

            'category_id'=>'required|exists:categories,id',

            'priority_id'=>'required|exists:priorities,id',

        ];
    }
}