<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use DomainException;

use App\Models\Download;
use App\Http\Requests\DownloadRequest;
use App\Services\DownloadService;


class MainController extends Controller {

    public function getLandingPage(): Response {
        return Inertia::render('App');
    }

    public function submitForm(DownloadRequest $request): JsonResponse {
        Log::debug('newDownloadRequest - called');
        $validated = $request->validated();
        // process the form
        $download = DownloadService::processForm(
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
            'data' => [
                'channelName' => $download->id,
            ],
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
