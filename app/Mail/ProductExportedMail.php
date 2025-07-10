<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Storage;

final class ProductExportedMail extends Mailable
{
    public string $s3Path;

    public function __construct(string $s3Path)
    {
        $this->s3Path = $s3Path;
    }

    public function build()
    {
        return $this->subject('Product Catalog Exported')
            ->view('emails.product_exported')
            ->with(['link' => Storage::disk('s3')->url($this->s3Path)]);
    }
}
