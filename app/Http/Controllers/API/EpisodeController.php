<?php

namespace App\Http\Controllers;

use App\Models\Episode;
use App\Http\Requests\StoreEpisodeRequest;
use App\Http\Requests\UpdateEpisodeRequest;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class EpisodeController extends Controller
{
    public function newAdded() {}
    public function editorsPick() {}
    public function trendingThisWeek() {}
}
