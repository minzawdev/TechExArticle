<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if ($this->has('qry')) {
            $this->merge([
                'qry' => $this->qry,
            ]);
        } else {
            $this->merge([
                'qry' => null,
            ]);
        }

        if ($this->hasHeader('cache')) {
            $this->merge([
                'cache' => $this->Header('cache'),
            ]);
        } else {
            $this->merge([
                'cache' => 1,
            ]);
        }

        $this->validate([
            'qry'           => 'max:255',
            'cache'         => 'boolean',
        ]);

        return [];
    }
}
