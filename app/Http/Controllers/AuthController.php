<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\Nova;
use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    /**
     * The URL generator instance.
     *
     * @var \Illuminate\Routing\UrlGenerator
     */
    protected UrlGenerator $generator;

    /**
     * Create a new Redirector instance.
     *
     * @param \Illuminate\Routing\UrlGenerator  $generator
     *
     * @return void
     */
    public function __construct(UrlGenerator $generator)
    {
        $this->generator = $generator;
    }

    /**
     * Returns the target URL of the application to the provider's authentication screen.
     *
     * @return string
     */
    public function redirect(Request $request)
    {
        $intended = $request->isMethod('GET') && $request->route() && !$request->expectsJson()
            ? $this->generator->full()
            : $this->generator->previous();

        appLog($intended);

        if ($intended) {
            $request->session()->put('url.intended', $intended);
        }

        /* @var \SocialiteProviders\GitHub\Provider $socialite */
        $socialite = Socialite::driver('github');

        return $socialite->redirect()->getTargetUrl();
    }

    /**
     * Receiving and handle the callback from the provider after authentication.
     *
     * @param \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function callback(Request $request)
    {
        /* @var \SocialiteProviders\Manager\OAuth2\User|\SocialiteProviders\GitHub\Provider $socialiteUser */
        $socialiteUser = Socialite::driver('github')->user();

        $user = User::updateOrCreate(
            ['github_id' => $socialiteUser->getId()],
            [
                'name' => $socialiteUser->getName() ?: $socialiteUser->getNickname(),
                'email' => $socialiteUser->getEmail(),
            ]
        );

        Auth::login($user);

        $this->handleLicenceCheck($request, $user);

        $intended = $request->session()->get('url.intended');

        return $intended ? redirect()->intended($intended) : redirect('/');
    }

    /**
     * Optional set User licence checked time.
     *
     * @param \Illuminate\Http\Request  $request
     * @param \App\Models\User          $user
     *
     * @return void
     */
    protected function handleLicenceCheck(Request $request, User $user): void
    {
        if ($user->save_licence) {
            $url = $user->licence_url;
            $key = $user->licence_key;
            if ($url && $key) {
                if (!Nova::getNovaLicenceValidationError($url, $key)) {
                    $user->update(['licence_checked_at' => now()]);

                    return;
                }
            }
        }

        $checked = $request->session()->get('licence_checked_at');
        if ($checked) {
            $user->update(['licence_checked_at' => $checked]);
        }
    }
}
