<?php

namespace App\Http\Requests;

use App\Rules\TimeSlotAvailable;
use Illuminate\Foundation\Http\FormRequest;

class FreeTimeRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */

    public function rules(): array
    {

        return [
            'day_of_week' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'salary' => 'required|numeric|min:0',
            'intersect' => [new TimeSlotAvailable(auth()->id(), $this->day_of_week)]
        ];

    }
}
