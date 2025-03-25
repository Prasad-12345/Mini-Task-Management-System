<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'id' => 'required|integer|exists:tasks,id',
            'title' => 'required|string|max:255',
            'priority' => 'required|in:Low,Medium,High',
            // 'due_date' => 'required|date|after:today',
            'due_date' => 'required|date',
            'status' => 'required|in:Pending,Completed'
        ];
    }
}
