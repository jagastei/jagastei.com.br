<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Constant;
use Illuminate\Http\Request;
use Inertia\Middleware;

final class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user()?->load([
                    'wallets',
                    'currentWallet',
                ]),
                'on_generic_trial' => $request->user()?->onGenericTrial(),
            ],

            'notify' => $request->session()->get('notify', []),

            'defaultLanguage' => Constant::DEFAULT_LANGUAGE,
            'availableLanguages' => Constant::AVAILABLE_LANGUAGES(),

            'defaultCurrency' => Constant::DEFAULT_CURRENCY,
            'availableCurrencies' => Constant::AVAILABLE_CURRENCIES(),
        ];
    }
}
