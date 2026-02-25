<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ClientOrder extends Mailable
{
    use Queueable, SerializesModels;

    public array $items;
    public array $userInfo;

    public function __construct(array $items, array $userInfo)
    {
        $this->items = array_map(static function (array $item): array {
            $item['options'] = $item['options'] ?? [];

            return $item;
        }, $items);

        $this->userInfo = $userInfo;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Ваш заказ принят',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.client_order',
            with: [
                'items' => $this->items,
                'userInfo' => $this->userInfo,
            ],
        );
    }
}
