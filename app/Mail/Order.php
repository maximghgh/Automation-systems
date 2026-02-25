<?php

namespace App\Mail;

use App\Models\EmailType;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Order extends Mailable
{
    use Queueable, SerializesModels;

    public array $items;
    public array $userInfo;
    public ?array $keoInfo;
    public array $storedAttachments;
    public ?EmailType $emailType;

    public function __construct(
        array $items,
        array $userInfo,
        ?array $keoInfo,
        array $storedAttachments = [],
        ?EmailType $emailType = null
    ) {
        $this->items = array_map(static function (array $item): array {
            if (isset($item['photo'])) {
                $item['photo_url'] = $item['photo'];
            }

            $item['options'] = $item['options'] ?? [];

            return $item;
        }, $items);

        $this->userInfo = $userInfo;
        $this->keoInfo = $keoInfo;
        $this->storedAttachments = $storedAttachments;
        $this->emailType = $emailType;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Заявка на приобретение товара',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.order',
            with: [
                'items' => $this->items,
                'userInfo' => $this->userInfo,
                'keoInfo' => $this->keoInfo,
                'attachment' => $this->storedAttachments,
                'emailType' => $this->emailType,
            ],
        );
    }

    public function attachments(): array
    {
        $result = [];

        foreach ($this->storedAttachments as $file) {
            $disk = $file['disk'] ?? 'public';
            $path = $file['path'] ?? null;

            if (! $path) {
                continue;
            }

            $name = $file['original_name'] ?? basename($path);

            $attachment = Attachment::fromStorageDisk($disk, $path)
                ->as($name);

            if (! empty($file['mime'])) {
                $attachment->withMime($file['mime']);
            }

            $result[] = $attachment;
        }

        return $result;
    }
}
