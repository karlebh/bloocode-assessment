<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
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
        $id = $this->route('id');
        $category = Category::find($id);

        if (! $category) {
            return $this->notFoundResponse("Could not find category with id : {$id}");
        }

        return [
            'name' => ['nullable', 'string', Rule::unique('categories')->ignore($category->name)],
            'description' => ['nullable', 'string', Rule::unique('categories')->ignore($category->description)],
        ];
    }
}
