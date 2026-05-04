<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\Call;
use App\Models\EmailType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CallControllers extends Controller
{
    public function call(Request $request): JsonResponse|RedirectResponse
    {
        $data = $request->validate([
            'email_type' => ['nullable', 'exists:email_types,id'],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'message' => ['required', 'string'],
            'consent' => ['accepted'],
        ], [
            'consent.accepted' => 'Примите условия оферты и политики конфиденциальности.',
        ]);

        $routing = $this->resolveRouting($request, $data['email_type'] ?? null);

        if ($routing instanceof JsonResponse || $routing instanceof RedirectResponse) {
            return $routing;
        }

        Mail::to($routing['recipients'])->send(new Call($data, $routing['emailType']));

        return $this->successResponse($request, 'Заявка успешно отправлена');
    }

    private function resolveRouting(Request $request, ?int $emailTypeId): array|JsonResponse|RedirectResponse
    {
        $emailType = $this->resolveEmailType($emailTypeId);

        if (! $emailType) {
            return $this->validationErrorResponse(
                $request,
                'email_type',
                'Не найден тип заявки с привязанными получателями'
            );
        }

        $recipients = $emailType->emails
            ->pluck('email')
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        if (empty($recipients)) {
            return $this->validationErrorResponse(
                $request,
                'email_type',
                'Для выбранного типа заявки не настроены получатели'
            );
        }

        return [
            'emailType' => $emailType,
            'recipients' => $recipients,
        ];
    }

    private function resolveEmailType(?int $emailTypeId): ?EmailType
    {
        if ($emailTypeId) {
            return EmailType::query()->with('emails')->find($emailTypeId);
        }

        return EmailType::query()
            ->with('emails')
            ->whereHas('emails')
            ->orderBy('id')
            ->first();
    }

    private function validationErrorResponse(
        Request $request,
        string $field,
        string $message
    ): JsonResponse|RedirectResponse {
        if ($this->shouldReturnJson($request)) {
            return response()->json(['message' => $message], 422);
        }

        return back()->withErrors([$field => $message])->withInput();
    }

    private function successResponse(Request $request, string $message): JsonResponse|RedirectResponse
    {
        if ($this->shouldReturnJson($request)) {
            return response()->json(['message' => $message]);
        }

        return back()->with('success', $message);
    }

    private function shouldReturnJson(Request $request): bool
    {
        return $request->expectsJson() || $request->is('api/*');
    }
}
