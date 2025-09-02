<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\Download;


class MainController extends Controller {

    public function getLandingPage() {
        return Inertia::render('App');
    }

    public function newDownloadRequest(Request $request) {
        $validated = $request->validate([
            'link' => 'required|url',
            'format' => 'required|string|in:mp3,m4a,flac,wav,aac,alac,opus,vorbis',
            'quality' => 'required|integer|min:0|max:10',
        ]);
        // check if the URL is a valid YouTube video URL
        // ...
        // add the download to the database
        Download::create([
            'youtube_url' => $validated['link'],
            'format' => $validated['format'],
            'quality' => $validated['quality'],
            'state_id' => 1
        ]);
        return Inertia::render('Test', [
            'link' => $validated['link'],
            'format' => $validated['format'],
            'quality' => $validated['quality'],
        ]);
    }

}
