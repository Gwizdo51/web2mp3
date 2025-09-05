<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Log;


class DownloadRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array {
        return [
            // 'link' => 'required|url',
            'link' => 'required|string',
            'format' => 'required|string|in:mp3,m4a,flac,wav,aac,alac,opus,vorbis',
            'quality' => 'required|integer|min:0|max:10',
        ];
    }

    protected function linkIsValid(): bool {
        // the link must be a valid URL
        if (!filter_var($this->link, FILTER_VALIDATE_URL)) {
            return false;
        }
        $urlParts = parse_url($this->link);
        // Log::debug(collect($urlParts));
        // this URL must have a host, a path and query parameters
        if (!array_key_exists('host', $urlParts) || !array_key_exists('path', $urlParts) || !array_key_exists('query', $urlParts)) {
            return false;
        }
        // the host must be YouTube
        if ($urlParts['host'] !== 'www.youtube.com') {
            return false;
        }
        // the link must point to a video
        if ($urlParts['path'] !== '/watch') {
            return false;
        }
        $queryParts = [];
        parse_str($urlParts['query'], $queryParts);
        // Log::debug(collect($queryParts));
        if (!array_key_exists('v', $queryParts)) {
            return false;
        }
        return true;
    }

    /**
     * Get the "after" validation callables for the request.
     *
     * @return (callable(Validator):void)[]
     */
    public function after(): array {
        return [
            function (Validator $validator) {
                if (!$this->linkIsValid()) {
                    $validator->errors()->add(
                        'link',
                        'The link must be a valid YouTube video URL.'
                    );
                }
            }
        ];
    }
}
