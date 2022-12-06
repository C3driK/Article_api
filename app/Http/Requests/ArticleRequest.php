<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'author_name' => ['required', 'string', 'min:3', 'max:100'],
            'body' => ['required', 'string', 'min:3', 'max:65535'],
            'should_publish' => ['required', 'boolean'],
            'expires_at' => ['nullable', 'date_format:Y-m-d H:i:s', 'after:now'],
        ];
    }

    public function validated($key = null, $default = null)
    {
        return [
            'name' => $this->string('name')->ucfirst(),
            'author_name' => $this->string('author_name')->title(),
            'body' => $this->string('body')->ucfirst(),
            'published_at' => $this->boolean('should_publish') ? now() : null,
            'expired_at' => $this->date('expires_at'),
        ];
    }
}
