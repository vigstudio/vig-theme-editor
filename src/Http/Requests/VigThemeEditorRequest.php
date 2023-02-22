<?php

namespace VigStudio\VigThemeEditor\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class VigThemeEditorRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => 'required',
            'status' => Rule::in(BaseStatusEnum::values()),
        ];
    }
}
