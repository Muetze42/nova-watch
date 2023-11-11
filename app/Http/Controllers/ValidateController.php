<?php

namespace App\Http\Controllers;

use App\Services\Nova;
use Illuminate\Http\Request;

class ValidateController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'url' => ['required', 'string'],
            'key' => ['required', 'string'],
            'save' => ['bool'],
        ]);

        if (!$message = Nova::getNovaLicenceValidationError($request->input('url'), $request->input('key'))) {
            $this->storeValidation($request);

            return ['success' => true];
        }

        return response(['message' => $message], 422);
    }

    /**
     * @param \Illuminate\Http\Request  $request
     *
     * @return void
     */
    protected function storeValidation(Request $request): void
    {
        $user = $request->user();
        if ($user) {
            $user->update(['licence_checked_at' => now()]);

            return;
        }

        $request->session()->put('licence_checked_at', now()->timestamp);
    }
}
