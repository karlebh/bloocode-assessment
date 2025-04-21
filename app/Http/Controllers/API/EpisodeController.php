<?php

namespace App\Http\Controllers;

use App\Models\Episode;
use App\Http\Requests\StoreEpisodeRequest;
use App\Http\Requests\UpdateEpisodeRequest;
use Illuminate\Support\Str;

class EpisodeController extends Controller
{
    public function store(StoreEpisodeRequest $request)
    {
        try {
            $episode =  Episode::create($request->validated() + ['slug' => Str::slug($request->validated()['title'])]);

            if (! $episode) {
                return $this->badRequestResponse('Could not create episode');
            }

            return $this->successResponse('Episode created successfully', ['episode' => $episode]);
        } catch (\Throwable $th) {
            return $this->serverErrorResponse('Server error', $th);
        }
    }

    public function update(UpdateEpisodeRequest $request, int $id)
    {
        try {
            $requestData = array_filter($request->validated());

            $episode =  Episode::find($id);

            if (! $episode) {
                return $this->notFoundResponse('Could not find episode');
            }

            if ($requestData['name']) {
                $requestData['slug'] = Str::slug($requestData['name']);
            }

            $episode->update($requestData);

            return $this->successResponse('Episode updated successfully', ['episode' => $episode->fresh()]);
        } catch (\Throwable $th) {
            return $this->serverErrorResponse('Server error', $th);
        }
    }

    public function show(int $id)
    {
        try {
            $episode =  Episode::find($id);

            if (! $episode) {
                return $this->notFoundResponse('Could not find episode');
            }

            return $this->successResponse('Episode retrieved successfully', ['episode' => $episode->fresh()]);
        } catch (\Throwable $th) {
            return $this->serverErrorResponse('Server error', $th);
        }
    }

    public function newlyAdded()
    {
        try {
            $lastweek = now()->subWeek();
            $thisweek = now();

            $episodes = Episode::whereBetween('created_at', [$lastweek, $thisweek])->get();

            if (! $episodes) {
                return $this->notFoundResponse('Could not find episodes');
            }

            return $this->successResponse('Episode(s) retrieved successfully', ['episodes' => $episodes]);
        } catch (\Throwable $th) {
            return $this->serverErrorResponse('Server error', $th);
        }
    }

    public function editorsPick()
    {
        try {
            $episodes = Episode::inRandomOrder()->take(10)->get();

            if (! $episodes) {
                return $this->notFoundResponse('Could not find episodes');
            }

            return $this->successResponse('Episode(s) retrieved successfully', ['episodes' => $episodes]);
        } catch (\Throwable $th) {
            return $this->serverErrorResponse('Server error', $th);
        }
    }


    public function trendingThisWeek()
    {
        try {
            $lastweek = now()->subWeek();
            $thisweek = now();

            $episodes = Episode::whereBetween('created_at', [$lastweek, $thisweek])->where('listen_count', '>', '20')->get();

            if (! $episodes) {
                return $this->notFoundResponse('Could not find episodes');
            }

            return $this->successResponse('Episode(s) retrieved successfully', ['episodes' => $episodes]);
        } catch (\Throwable $th) {
            return $this->serverErrorResponse('Server error', $th);
        }
    }
}
