<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEpisodeRequest extends FormRequest
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
            'user_id' => ['required', 'exists:users,id'],
            'podcast_id' => ['required', 'exists:podcasts,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'audio_url' => ['required', 'url'],
            'duration' => ['required', 'integer', 'min:1'],
            'episode_number' => ['required', 'integer', 'min:1'],
            'summary' => ['nullable', 'string'],
            'release_date' => ['required', 'date'],
        ];
    }
}
