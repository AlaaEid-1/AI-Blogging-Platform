<?php

namespace App\Http\Requests;

use App\Rules\Restricted;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Override;

class PostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if ($this->method() == 'put') {
        }
        $id = $this->route('post', 0);

        return [
            //'title' => ['sometimes', 'required', 'min:3', 'max:255'],
            'title' => ['required', 'min:3', 'max:255', Rule::unique('posts', 'title')->ignore($id)],
            'content' => [
                'required',
                'string',
                'max:999999',
                new Restricted(['god'])
            ],
            'cover' => [
                'nullable',
                'image',
                'mimetypes:image/png,image/jpeg',
                // 'dimensions:min_width=100,min_height=200,max_width=2000,max_height=2000',
                'max:1024'
            ],
            'tags' => ['nullable', 'string'],
            'status' => ['required', Rule::enum(\App\Enums\PostStatus::class)],
            'published_at' => ['nullable', 'date'],
            'meta' => ['nullable', 'array'],
            'meta.title' => ['nullable', 'string', 'max:255'],
            'meta.keywords' => ['nullable', 'string', 'max:255'],
            'meta.url' => ['nullable', 'url'],
            'meta.description' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [];
        // return [
        //     'required' => ':attribute is required!',
        //     'title.min' => ':attribute :min is mandatory',
        // ];
    }

    #[Override]
    public function attributes(): array
    {
        return [
            'title' => 'post title',
            'content' => 'post body',
            'cover' => 'cover image',
        ];
    }
}
