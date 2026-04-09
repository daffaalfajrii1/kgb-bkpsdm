<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Services\PegawaiAksesDisiplinService;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $login = $this->string('login')->toString();
        $remember = $this->boolean('remember');

        // Jika login dengan NIP, cek SKP / hukuman sebelum attempt
        if (! filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $nip = PegawaiAksesDisiplinService::normalizeNip($login);

            if ($nip !== '') {
                $user = User::query()->where('nip', $nip)->first();

                if ($user) {
                    $block = PegawaiAksesDisiplinService::pesanBlokirLogin($user);
                    if ($block) {
                        PegawaiAksesDisiplinService::logBlokir('login', $user, $block, ['via' => 'admin_login_form']);

                        if (PegawaiAksesDisiplinService::blokirLoginKarenaSkp($user)) {
                            session()->flash('login_blokir_skp', true);
                            session()->flash('pesan_blokir_login_skp', $block);
                        }

                        throw ValidationException::withMessages([
                            'login' => $block,
                        ]);
                    }
                }
            }
        }

        // Coba sebagai admin (email) di guard web
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            if (Auth::guard('web')->attempt([
                'email' => $login,
                'password' => $this->string('password')->toString(),
            ], $remember)) {
                RateLimiter::clear($this->throttleKey());
                return;
            }
        } else {
            $nipForAttempt = PegawaiAksesDisiplinService::normalizeNip($login);
            $nipCredential = $nipForAttempt !== '' ? $nipForAttempt : $login;

            if (Auth::guard('pegawai')->attempt([
                'nip' => $nipCredential,
                'password' => $this->string('password')->toString(),
            ], $remember)) {
                RateLimiter::clear($this->throttleKey());
                return;
            }
        }

        RateLimiter::hit($this->throttleKey());

        throw ValidationException::withMessages([
            'login' => trans('auth.failed'),
        ]);
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('login')).'|'.$this->ip());
    }
}
