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

        if ($this->has('cache')) {
            $this->merge([
                'cache' => $this->cache,
            ]);
        } else {
            $this->merge([
                'cache' => true,
            ]);
        }
        $this->validate([
            'qry'           => 'max:255',
            'cache'         => 'required|boolean',
        ]);

        return [];
    }
}
