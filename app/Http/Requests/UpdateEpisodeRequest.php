<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEpisodeRequest extends FormRequest
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
            'podcast_id' => ['nullable', 'exists:podcasts,id'],
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'audio_url' => ['nullable', 'url'],
            'duration' => ['nullable', 'integer', 'min:1'],
            'episode_number' => ['nullable', 'integer', 'min:1'],
            'summary' => ['nullable', 'string'],
            'release_date' => ['nullable', 'date'],
        ];
    }
}
