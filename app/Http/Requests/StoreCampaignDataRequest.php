<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCampaignDataRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            '*.user_id' => ['required', 'string', 'max:255'],
            '*.video_url' => ['required', 'url', 'max:2048'],
            '*.custom_fields' => ['nullable', 'array'],
        ];
    }

    public function messages(): array
    {
        return [
            '*.user_id.required' => 'Each campaign data item must include a user_id.',
            '*.video_url.required' => 'Each campaign data item must include a video_url.',
            '*.video_url.url' => 'Each video_url must be a valid URL.',
            '*.custom_fields.array' => 'custom_fields must be an object/associative array.',
        ];
    }
}