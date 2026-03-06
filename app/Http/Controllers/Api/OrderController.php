<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\ClientOrder;
use App\Mail\Order;
use App\Models\EmailType;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function submitForm(Request $request): JsonResponse|RedirectResponse
    {
        $prepared = $this->prepareInput($request);

        $validatedData = validator($prepared, [
            'items' => ['required', 'array', 'min:1'],
            'items.*.name' => ['required', 'string'],
            'items.*.url' => ['required', 'string', 'max:2048'],
            'items.*.photo' => ['sometimes'],
            'items.*.quantity' => ['nullable', 'numeric'],
            'items.*.options' => ['nullable', 'array'],
            'items.*.options.*.name' => ['nullable', 'string'],
            'items.*.options.*.values' => ['nullable', 'array'],
            'items.*.options.*.values.*.value' => ['nullable', 'string'],
            'userInfo' => ['required', 'array', 'min:1'],
            'userInfo.*.name' => ['required', 'string'],
            'userInfo.*.phone' => ['required', 'string'],
            'userInfo.*.email' => ['required', 'email'],
            'keoInfo' => ['nullable', 'array'],
            'keoInfo.*.city' => ['nullable', 'string'],
            'keoInfo.*.company_name' => ['nullable', 'string'],
            'keoInfo.*.job_title' => ['nullable', 'string'],
            'keoInfo.*.object_address' => ['nullable', 'string'],
            'keoInfo.*.contact_method' => ['nullable', 'string', 'in:telegram,whatsapp,phone,email'],
            'keoInfo.*.request_features' => ['nullable', 'array'],
            'keoInfo.*.request_features.*' => ['nullable', 'string', 'in:before_expertise,after_expertise,keo_calc,system_selection'],
            'keoInfo.*.description' => ['nullable', 'string'],
            'attachment' => ['sometimes', 'array'],
            'attachment.*' => ['file', 'mimes:pdf,doc,docx,png,jpg,jpeg,zip', 'max:51200'],
            'email-type' => ['nullable', 'exists:email_types,id'],
        ])->validate();

        $items = array_map(static function (array $item): array {
            $item['options'] = $item['options'] ?? [];

            return $item;
        }, $validatedData['items']);

        $keo = null;
        if (! empty($validatedData['keoInfo']) && is_array($validatedData['keoInfo'])) {
            $keo = $validatedData['keoInfo'][0] ?? null;
        }

        $storedAttachments = $this->storeAttachments(
            $validatedData['attachment']
                ?? $request->file('attachment')
                ?? $request->file('files')
        );

        [$emailType, $emailAddresses] = $this->resolveRecipients($request);

        $user = $validatedData['userInfo'][0];

        try {
            Mail::to($emailAddresses)->send(
                new Order($items, $validatedData['userInfo'], $keo, $storedAttachments, $emailType)
            );

            Mail::to($user['email'])->send(
                new ClientOrder($items, $user)
            );
        } finally {
            foreach ($storedAttachments as $file) {
                if (! empty($file['path'])) {
                    Storage::disk($file['disk'] ?? 'public')->delete($file['path']);
                }
            }
        }

        if ($this->shouldReturnJson($request)) {
            return response()->json([
                'message' => 'OK',
            ], 200);
        }

        return back()->with('success', 'Заказ успешно отправлен');
    }

    private function prepareInput(Request $request): array
    {
        $input = $request->all();

        foreach (['items', 'userInfo', 'keoInfo'] as $field) {
            if (isset($input[$field]) && is_string($input[$field])) {
                $decoded = json_decode($input[$field], true);

                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $input[$field] = $decoded;
                }
            }
        }

        if (! isset($input['email-type']) && isset($input['email_type'])) {
            $input['email-type'] = $input['email_type'];
        }

        if (! isset($input['attachment']) && isset($input['files'])) {
            $input['attachment'] = $input['files'];
        }

        if ((! isset($input['items']) || ! is_array($input['items']) || empty($input['items']))
            && isset($input['products']) && is_array($input['products'])) {
            $input['items'] = $this->buildItemsFromProductsSelection($input['products']);
        }

        if ((! isset($input['userInfo']) || ! is_array($input['userInfo']) || empty($input['userInfo']))
            && isset($input['name'], $input['phone'], $input['email'])) {
            $input['userInfo'] = [[
                'name' => (string) $input['name'],
                'phone' => (string) $input['phone'],
                'email' => (string) $input['email'],
            ]];
        }

        if ((! isset($input['keoInfo']) || ! is_array($input['keoInfo']) || empty($input['keoInfo']))
            && isset($input['description'])) {
            $input['keoInfo'] = [[
                'description' => (string) $input['description'],
            ]];
        }

        if (isset($input['items']) && is_array($input['items'])) {
            $input['items'] = $this->normalizeItems($input['items']);
        }

        if (isset($input['userInfo']) && is_array($input['userInfo']) && $this->isAssoc($input['userInfo'])) {
            $input['userInfo'] = [$input['userInfo']];
        }

        if (isset($input['keoInfo']) && is_array($input['keoInfo']) && $this->isAssoc($input['keoInfo'])) {
            $input['keoInfo'] = [$input['keoInfo']];
        }

        if (isset($input['attachment']) && $input['attachment'] instanceof UploadedFile) {
            $input['attachment'] = [$input['attachment']];
        }

        return $input;
    }

    private function buildItemsFromProductsSelection(array $productsSelection): array
    {
        return collect($productsSelection)
            ->map(function ($item, $productId): ?array {
                if (! is_array($item)) {
                    return null;
                }

                $id = filter_var($productId, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

                if (! $id) {
                    return null;
                }

                $isSelected = filter_var($item['selected'] ?? false, FILTER_VALIDATE_BOOLEAN);

                if (! $isSelected) {
                    return null;
                }

                $quantity = isset($item['quantity']) ? (int) $item['quantity'] : 1;

                if ($quantity < 1) {
                    $quantity = 1;
                }

                return [
                    'product_id' => $id,
                    'quantity' => $quantity,
                    'options' => [],
                ];
            })
            ->filter()
            ->values()
            ->all();
    }

    private function normalizeItems(array $rawItems): array
    {
        $items = $rawItems;

        if ($this->isAssoc($items)) {
            $first = reset($items);
            $items = is_array($first) ? array_values($items) : [$items];
        }

        $items = array_values(array_filter($items, static fn ($item): bool => is_array($item)));

        $productIds = collect($items)
            ->map(static fn (array $item): ?int => isset($item['id']) ? (int) $item['id'] : (isset($item['product_id']) ? (int) $item['product_id'] : null))
            ->filter(static fn (?int $id): bool => ! empty($id))
            ->unique()
            ->values();

        $products = $productIds->isEmpty()
            ? collect()
            : Product::query()
                ->whereIn('id', $productIds->all())
                ->get(['id', 'title', 'slug', 'image'])
                ->keyBy('id');

        return array_map(function (array $item) use ($products): array {
            $id = isset($item['id']) ? (int) $item['id'] : (isset($item['product_id']) ? (int) $item['product_id'] : null);
            $product = $id ? $products->get($id) : null;

            if (empty($item['name']) && ! empty($item['title'])) {
                $item['name'] = $item['title'];
            }

            if (empty($item['name']) && $product) {
                $item['name'] = $product->title;
            }

            if (empty($item['photo']) && ! empty($item['image'])) {
                $item['photo'] = $item['image'];
            }

            if (empty($item['photo']) && $product && ! empty($product->image)) {
                $item['photo'] = $product->image;
            }

            if (! empty($item['photo']) && is_string($item['photo'])) {
                $item['photo'] = $this->resolvePublicAssetUrl($item['photo']);
            }

            if (empty($item['url']) && ! empty($item['slug'])) {
                $item['url'] = route('products.show', ['product' => $item['slug']]);
            }

            if (empty($item['url']) && $product) {
                $item['url'] = route('products.show', ['product' => $product->slug]);
            }

            if (! empty($item['url']) && is_string($item['url'])) {
                if (str_starts_with($item['url'], '/')) {
                    $item['url'] = url($item['url']);
                }
            }

            $item['options'] = $item['options'] ?? [];
            $item['quantity'] = isset($item['quantity']) ? (int) $item['quantity'] : 1;

            if ($item['quantity'] < 1) {
                $item['quantity'] = 1;
            }

            return $item;
        }, $items);
    }

    private function resolvePublicAssetUrl(string $value): string
    {
        if (preg_match('/^https?:\\/\\//i', $value) === 1) {
            return $value;
        }

        if (str_starts_with($value, '/storage/')) {
            return url($value);
        }

        if (str_starts_with($value, 'storage/')) {
            return url('/'.$value);
        }

        $storageUrl = Storage::disk('public')->url($value);

        if (str_starts_with($storageUrl, 'http://') || str_starts_with($storageUrl, 'https://')) {
            return $storageUrl;
        }

        return url($storageUrl);
    }

    private function isAssoc(array $array): bool
    {
        return $array !== [] && array_keys($array) !== range(0, count($array) - 1);
    }

    private function storeAttachments(array|UploadedFile|null $attachments): array
    {
        $files = $this->flattenUploadedFiles($attachments);

        $storedAttachments = [];

        foreach ($files as $file) {
            $path = $file->store('orders/tmp', 'public');

            $storedAttachments[] = [
                'disk' => 'public',
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'mime' => $file->getClientMimeType() ?: 'application/octet-stream',
            ];
        }

        return $storedAttachments;
    }

    private function flattenUploadedFiles(array|UploadedFile|null $attachments): array
    {
        if ($attachments instanceof UploadedFile) {
            return $attachments->isValid() ? [$attachments] : [];
        }

        if (! is_array($attachments)) {
            return [];
        }

        $result = [];

        foreach ($attachments as $file) {
            if ($file instanceof UploadedFile) {
                if ($file->isValid()) {
                    $result[] = $file;
                }

                continue;
            }

            if (is_array($file)) {
                $result = array_merge($result, $this->flattenUploadedFiles($file));
            }
        }

        return $result;
    }

    private function resolveRecipients(Request $request): array
    {
        $type = null;
        $typeId = $request->input('email-type');

        if (! empty($typeId)) {
            $type = EmailType::with('emails')->find($typeId);
        }

        if (! $type) {
            $type = EmailType::query()
                ->with('emails')
                ->where(function ($query) {
                    $query
                        ->whereRaw('LOWER(type) = ?', ['order'])
                        ->orWhereRaw('LOWER(type) = ?', ['заявка']);
                })
                ->first();
        }

        if (! $type) {
            throw ValidationException::withMessages([
                'email-type' => 'Тип заявки "order" или "заявка" не найден.',
            ]);
        }

        $emails = $type->emails
            ->pluck('email')
            ->filter()
            ->unique()
            ->values()
            ->all();

        if (empty($emails)) {
            throw ValidationException::withMessages([
                'email-type' => 'Для типа заявки не настроены email получателей.',
            ]);
        }

        return [$type, $emails];
    }

    private function shouldReturnJson(Request $request): bool
    {
        return $request->expectsJson() || $request->is('api/*');
    }
}
