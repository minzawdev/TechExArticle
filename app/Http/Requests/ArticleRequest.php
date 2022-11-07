<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $this->validate([
            'title' => 'required|max:100',
            'body'  => 'required|max:255'
        ],$this->message());

        return [];
    }

    public function message()
    {
        return [
            'title.required'=> 'Article title is required.',
            'title.max'     => 'Article title is not greater than 100 characters.',
            'body.required' => 'Article body is required.',
            'body.max'      => 'Article body is not greater than 255 characters.'
        ];
    }
}
