<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\Auth\Login as AuthLoginRequest;
use App\Http\Requests\Api\V1\Auth\Logout as AuthLogoutRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\NewAccessToken;

/**
 * API авторизации и аутентификации пользователя по AJAX.
 * Создан согласно документации:
 *     `https://laravel.com/docs/8.x/authentication#authenticating-users`.
 */
class AuthController extends ApiController
{
    /**
     * Создать экземпляр контроллера.
     */
    public function __construct()
    {
    }

    /**
     * Авторизация пользователя в административной панели.
     * Производим идентификацию по электронной почте и паролю.
     *
     * @param  AuthLoginRequest $request
     * @return JsonResponse
     */
    public function login(AuthLoginRequest $request)
    {
        if ($this->attemptLogin($request)
            and $this->checkHashedPassword($request)
            and $newAccessToken = $this->guardUser()->createToken('api_token')
        ) {

            return $this->sendLoginResponse($newAccessToken);
        }

        return $this->sendFailedLoginResponse();
    }

    /**
     * Сбросить ключ Api пользователя, чтобы он считался неаутентифицированным.
     *
     * @param  AuthLogoutRequest $request
     * @return JsonResponse
     */
    public function logout(AuthLogoutRequest $request): JsonResponse
    {
        $this->guard()->logout();

        $user = auth('sanctum')->user();

        $user->tokens()->delete();

        return response()
            ->json(null, JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * Дополнительная проверка на совпадение хэшей паролей.
     * Неизвестно насколько это оправдано.
     *
     * @param  Request  $request
     * @return bool
     */
    protected function checkHashedPassword(Request $request): bool
    {
        return Hash::check(
            $request->password,
            $this->guardUser()->getAuthPassword()
        );
    }

    /**
     * Попытаться авторизовать пользователя.
     *
     * @param  Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request): bool
    {
        return $this->guard()
            ->attempt(
                $this->credentials($request),
                $request->filled('remember')
            );
    }

    /**
     * Получить данные для авторизации из запроса.
     *
     * @param  Request  $request
     * @return array
     */
    protected function credentials(Request $request): array
    {
        return $request->only($this->username(), 'password');
    }

    /**
     * Получить параметр в качестве имени для авторизации.
     *
     * @return string
     */
    protected function username(): string
    {
        return 'email';
    }

    /**
     * Получить посредника, который используется во время аутентификации.
     *
     * @return StatefulGuard
     */
    protected function guard(): StatefulGuard
    {
        return Auth::guard();
    }

    /**
     * Получить авторизованного пользователя из посредника.
     *
     * @return User
     */
    protected function guardUser(): User
    {
        return $this->guard()->user();
    }

    /**
     * Отправить ответ после того как пользователь был аутентифицирован.
     * Генерируем новый `api` ключ для пользователя.
     *
     * @param  NewAccessToken  $newAccessToken
     * @return JsonResponse
     */
    protected function sendLoginResponse(NewAccessToken $newAccessToken)
    {
        $user = $this->guardUser();

        $resource = new UserResource($user);

        return $resource->response()
            ->header('api_token', $newAccessToken->plainTextToken)
            ->setStatusCode(JsonResponse::HTTP_ACCEPTED);
    }

    /**
     * Отправить ответ, если
     * попытка аутентификации пользователя оказалась неудачной.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws ValidationException
     */
    protected function sendFailedLoginResponse()
    {
        throw ValidationException::withMessages([
            $this->username() => [
                trans('auth.failed'),

            ],
        ]);
    }
}
