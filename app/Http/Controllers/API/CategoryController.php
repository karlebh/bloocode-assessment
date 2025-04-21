<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        try {
            $requestData = $request->validate(['q' => 'string']);

            $categories = Category::query();

            if ($request->filled('q')) {
                $categories
                    ->where('name', 'like', '%' . $requestData['q'] . '%')
                    ->orWhere('slug', 'like', '%' . $requestData['q'] . '%');
            }

            $paginated = $categories->latest()->paginate(10);

            $groupedCategories = $paginated->getCollection()->groupBy('name');

            return $this->successResponse('All Categories', ['categories' => $groupedCategories]);
        } catch (\Throwable $th) {
            return $this->serverErrorResponse('Server error', $th);
        }
    }

    public function store(StoreCategoryRequest $request)
    {
        try {
            $category = Category::create([
                ...$request->validated(),
                'slug' => Str::slug(
                    $request->validated()['name']
                ),
            ]);

            if (! $category) {
                return $this->badRequestResponse('Could not create category');
            }

            return $this->successResponse('Category created successfully', ['category' => $category]);
        } catch (\Throwable $th) {
            return $this->serverErrorResponse('Server error', $th);
        }
    }

    public function show(int $id)
    {
        try {
            $category = Category::with('podcast')->find($id);

            if (! $category) {
                return $this->notFoundResponse("Could not find category with id : {$id}");
            }

            return $this->successResponse('Category retrieved succesfully', ['category' => $category]);
        } catch (\Throwable $th) {
            return $this->serverErrorResponse('Server error', $th);
        }
    }


    public function update(UpdateCategoryRequest $request, int $id)
    {
        try {
            $requestData = $request->validated();

            $category = Category::with('podcast')->find($id);

            if (! $category) {
                return $this->notFoundResponse("Could not find category with id : {$id}");
            }

            $category->name =  $requestData['name'];
            $category->slug =  $requestData['slug'] ? Str::slug($requestData['slug']) : '';
            $category->description =  $requestData['description'];
            $category->save();

            return $this->successResponse('Category updated successfully', ['category' => $category->fresh()]);
        } catch (\Throwable $th) {
            return $this->serverErrorResponse('Server error', $th);
        }
    }

    public function destroy(int $id)
    {
        try {
            $category = Category::find($id);

            if (! $category) {
                return $this->notFoundResponse("Could not find category with id : {$id}");
            }

            $category->delete();

            return $this->successResponse('Category deleted successfully');
        } catch (\Throwable $th) {
            return $this->serverErrorResponse('Server error', $th);
        }
    }
}
