<?php

namespace App\Mails;

trait ToManyMailTrait
{
    public function to($address, $name = null)
    {
        if (! is_array($address) && \Illuminate\Support\Str::contains($address, ',')) {
            $address = explode(',', $address);
        }
        return $this->setAddress($address, $name, 'to');
    }

    public function cc($address, $name = null)
    {
        if (! is_array($address) && \Illuminate\Support\Str::contains($address, ',')) {
            $address = explode(',', $address);
        }
        return $this->setAddress($address, $name, 'to');
    }
}
