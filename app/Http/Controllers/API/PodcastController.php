<?php

namespace App\Http\Controllers;

use App\Models\Podcast;
use App\Http\Requests\StorePodcastRequest;
use App\Http\Requests\UpdatePodcastRequest;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class PodcastController extends Controller
{
    public function index(Request $request)
    {
        try {
            $requestData = $request->validate(['q' => 'string']);

            $cacheKey = 'podcasts:' . md5($request->fullUrl());

            $result = Cache::remember($cacheKey, now()->addMinutes(60), function () use ($requestData) {
                $query = Podcast::query()->with('episodes');

                if (! $requestData['q']) {
                    $query->where(function ($q) use ($requestData) {
                        $q->where('name', 'like', '%' . $requestData['q'] . '%')
                            ->orWhere('slug', 'like', '%' . $requestData['q'] . '%');
                    });
                }

                $paginated = $query->latest()->paginate(30);
                $grouped = $paginated->getCollection()->groupBy('name');

                return [
                    'podcasts' => $grouped,
                ];
            });

            return $this->successResponse('All Podcasts', ['podcasts' => $result]);
        } catch (\Throwable $th) {
            return $this->serverErrorResponse('Server error', $th);
        }
    }

    public function store(StorePodcastRequest $request)
    {
        try {
            $requestData = $request->validated();

            if ($request->hasFile('img_url')) {
                $file = $request->file('img_url');
                $name = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('uploads', $name, 'public');
                $requestData['image_url'] = $filePath;
            }

            $podcast = Podcast::create(Arr::except($requestData, ['image_url']) + [
                'user_id' => auth()->id(),
                'slug' => Str::slug($requestData['name']),
            ]);

            if (! $podcast) {
                return $this->badRequestResponse("Could not create podcast");
            }

            return $this->successResponse('Podcast created successfully', ['podcast' => $podcast]);
        } catch (\Throwable $th) {
            return $this->serverErrorResponse('Server error', $th);
        }
    }

    public function show(int $id)
    {
        try {
            $podcast = Podcast::with('episodes')->find($id);

            if (! $podcast) {
                return $this->notFoundResponse("Could not find podcast");
            }

            return $this->successResponse('Podcast retrieved successfully', ['podcast' => $podcast]);
        } catch (\Throwable $th) {
            return $this->serverErrorResponse('Server error', $th);
        }
    }

    public function update(UpdatePodcastRequest $request, int $id)
    {
        try {
            $requestData = array_filter($request->validated());

            $podcast = Podcast::with('episodes')->find($id);

            if (! $podcast) {
                return $this->notFoundResponse("Could not find podcast");
            }

            if ($requestData['name']) {
                $requestData['slug'] = Str::slug($requestData['name']);
            }

            if ($request->hasFile('img_url')) {
                $file = $request->file('img_url');
                $name = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('uploads', $name, 'public');
                $requestData['image_url'] = $filePath;
            }

            $podcast->update($requestData);

            return $this->successResponse('Podcast updated successfully', ['podcast' => $podcast->fresh()]);
        } catch (\Throwable $th) {
            return $this->serverErrorResponse('Server error', $th);
        }
    }

    public function destroy(int $id)
    {
        try {
            $podcast = Podcast::find($id);

            if (! $podcast) {
                return $this->notFoundResponse("Could not find podcast with id : {$id}");
            }

            $podcast->delete();

            return $this->successResponse('Category deleted successfully');
        } catch (\Throwable $th) {
            return $this->serverErrorResponse('Server error', $th);
        }
    }
}
