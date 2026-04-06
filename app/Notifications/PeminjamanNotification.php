<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PeminjamanNotification extends Notification
{
    use Queueable;

    public $isi; // Gunakan properti publik agar bisa diakses di toDatabase

    public function __construct($payload)
    {
        $this->isi = $payload;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'peminjaman_id' => $this->isi['id'],
            'title'         => $this->isi['title'],
            'message'       => $this->isi['message'],
            'type'          => $this->isi['type'],
            'url'           => $this->isi['url'] ?? '#',
        ];
    }
}