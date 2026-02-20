<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\Call;
use App\Models\EmailType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CallControllers extends Controller
{
    public function call(Request $request){
        $data = $request->validate([
            'email_type' => ['nullable', 'exists:email_types,id'],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'message' => ['required', 'string'],
        ]);

        $emailType = null;

        if (! empty($data['email_type'])) {
            $emailType = EmailType::with('emails')->findOrFail($data['email_type']);
        } else {
            $emailType = EmailType::query()
                ->with('emails')
                ->whereHas('emails')
                ->orderBy('id')
                ->first();
        }

        if (! $emailType) {
            $message = 'Не найден тип заявки с привязанными получателями';

            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 422);
            }

            return back()->withErrors(['email_type' => $message])->withInput();
        }

        $recipients = $emailType->emails
            ->pluck('email')
            ->unique()
            ->values()
            ->toArray();

        if(empty($recipients)){
            $message = 'Для выбранного типа заявки не настроены получатели';

            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 422);
            }

            return back()->withErrors(['email_type' => $message])->withInput();
        }

        Mail::to($recipients)->send(new Call($data, $emailType));

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Заявка успешно отправлена',
            ]);
        }

        return back()->with('success', 'Заявка успешно отправлена');
    }
}
