<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use App\Models\User;

class VerifyEmailController extends Controller
{
    public function verify(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = User::find($request->route('id'));

        if ($user->hasVerifiedEmail()) {
            return redirect(config('lcm.spa_url') . '/email/verify/already');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect(config('lcm.spa_url') . '/email/verify/success');
    }

    public function resend(Request $request): JsonResponse
    {
        $request->user()->sendEmailVerificationNotification();

        return response()->json(['message', 'Un nouveau lien de Verification a été envoyé!']);
    }
}