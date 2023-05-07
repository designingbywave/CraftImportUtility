<?php

namespace wavedesign\crafthrcommencementimportutility\models;

use craft\base\Model;

class Settings extends Model
{
    public $folderId = 1;
    public $fieldId = 1;

    public function rules(): array
    {
        return [
            [['folderId,fieldId'], 'required'],
            // ...
        ];
    }
}
