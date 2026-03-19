<?php

namespace App\Filament\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Schemas\Components\Component;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\ValidationException;

class Login extends BaseLogin
{
    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.password' => __('filament-panels::auth/pages/login.messages.failed'),
        ]);
    }

    protected function getEmailFormComponent(): Component
    {
        return parent::getEmailFormComponent()
            ->extraFieldWrapperAttributes(fn (): array => [
                'class' => $this->shouldHighlightBothLoginFields() ? 'sa-login-field-force-invalid' : null,
            ]);
    }

    protected function shouldHighlightBothLoginFields(): bool
    {
        /** @var MessageBag $errorBag */
        $errorBag = $this->getErrorBag();

        $passwordErrors = $errorBag->get('data.password');

        if ($passwordErrors === []) {
            return false;
        }

        $invalidCredentialsMessage = __('filament-panels::auth/pages/login.messages.failed');

        return in_array($invalidCredentialsMessage, $passwordErrors, true);
    }
}
