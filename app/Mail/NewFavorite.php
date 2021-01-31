<?php

namespace App\Mail;

use App\Models\Favorite;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewFavorite extends Mailable
{
    use Queueable, SerializesModels;

    public $favorite;

    /**
     * Create a new message instance.
     *
     * @param Favorite $favorite
     */
    public function __construct(Favorite $favorite)
    {
        $this->favorite = $favorite;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.favorites.new');

    }
}
