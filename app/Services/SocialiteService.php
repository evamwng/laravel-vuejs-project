<?php


namespace App\Services;


use App\Helpers\SocialiteHelper;
use App\Services\Contracts\SocialiteServiceInterface;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class SocialiteService implements SocialiteServiceInterface
{
    public function getRedirectUrlByProvider($provider): array
    {
        return [
            'redirectUrl' => Socialite::driver($provider)
                ->stateless()
                ->redirect()
                ->getTargetUrl()
        ];
    }

    public function loginWithSocialite($provider): array
    {
        $userSocial = Socialite::driver($provider)->stateless()->user();
        if (SocialiteHelper::isSocialPresent($userSocial)) {
            $user = $this->searchUserByEmail($userSocial->email);
            if ($user) {
                return $this->prepareSuccessResult($user);
            } else {
                $newUser                  = new User;
                $newUser->name            = $userSocial->name;
                $newUser->email           = $userSocial->email;
                $newUser->socialite_id       = $userSocial->id;
                return $newUser->save()
                    ? $this->prepareSuccessResult($newUser)
                    : $this->prepareErrorResult();
            }
        } else {
            return $this->prepareErrorResult();
        }
    }

    private function makeAuthenticationCookie($result)
    {
        $result['cookie'] = cookie('authentication',
            json_encode($result),
            8000,
            null,
            null,
            false,
            false
        );
        return $result;
    }

    private function searchUserByEmail($email): ?User
    {
        return User::where('email', $email)
            ->first();
    }

    private function prepareErrorResult(): array
    {
        return $this->makeAuthenticationCookie([
            'error' => 'User is unavailable. Try another social account!',
            'redirect' => '/login',
            'redirect_url' => '/#/',
        ]);
    }

    private function prepareSuccessResult(User $user): array
    {
        $token = $user->createToken('auth_token')->plainTextToken;
        return $this->makeAuthenticationCookie([
            'api_token' => $token,
            'user_id' => $user->id,
            'redirect_url' => '/#/home'
        ]);
    }
}
