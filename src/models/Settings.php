<?php

namespace wavedesign\crafthrcommencementimportutility\models;

use craft\base\Model;

class Settings extends Model
{
    public $assetpath = "/assets";

    public function rules(): array
    {
        return [
            [['assetpath'], 'required'],
            // ...
        ];
    }
}
