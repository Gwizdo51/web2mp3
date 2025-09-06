<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use DomainException;

// use App\Http\Requests\DownloadRequest;
use App\Services\DownloadService;


class MainController extends Controller {

    public function getLandingPage(): Response {
        return Inertia::render('App');
    }

    public function submitForm(Request $request): JsonResponse {
        Log::debug('newDownloadRequest - called');
        $validated = $request->validate([
            'link' => 'required|url',
            'format' => 'required|string|in:mp3,m4a,flac,wav,aac,alac,opus,vorbis',
            'quality' => 'required|integer|min:0|max:10',
        ]);
        // process the form
        $downloadData = DownloadService::processForm(
            $validated['link'],
            $validated['format'],
            $validated['quality'],
        );
        // return Inertia::render('Test', [
        //     'link' => $validated['link'],
        //     'format' => $validated['format'],
        //     'quality' => $validated['quality'],
        // ]);
        return response()->json([
            'success' => true,
            'message' => 'Download process queued',
            'data' => $downloadData,
            'errors' => [],
        ]);
    }

    public function getDownloadStatus(string $downloadID): JsonResponse {
        // return the status of the download with the requested ID
        try {
            $jsonResponse = [
                'success' => true,
                'message' => '',
                'data' => DownloadService::getStatus($downloadID),
                'errors' => [],
            ];
            $responseCode = 200;
        }
        catch (DomainException $e) {
            $jsonResponse = [
                'success' => false,
                'message' => $e->getMessage(),
                'data' => [],
                'errors' => [],
            ];
            $responseCode = 404;
        }
        return response()->json($jsonResponse, $responseCode);
    }

}
