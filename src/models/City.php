<?php
/**
 * RegistrarImporterCraft plugin for Craft CMS 3.x
 *
 * A plugin for importing Excel files
 *
 * @link      https://tdlacct.github.io/
 * @copyright Copyright (c) 2023 T Luce
 */

namespace wave\registrarimportercraft\models;

use wave\registrarimportercraft\RegistrarImporterCraft;

use Craft;
use craft\base\Model;

/**
 * City Model
 *
 * Models are containers for data. Just about every time information is passed
 * between services, controllers, and templates in Craft, it’s passed via a model.
 *
 * https://craftcms.com/docs/plugins/models
 *
 * @author    T Luce
 * @package   RegistrarImporterCraft
 * @since     1.0.0
 */
class City extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * Some model attribute
     *
     * @var string
     */
    public $cityname;

    // Public Methods
    // =========================================================================

    /**
     * Returns the validation rules for attributes.
     *
     * Validation rules are used by [[validate()]] to check if attribute values are valid.
     * Child classes may override this method to declare different validation rules.
     *
     * More info: http://www.yiiframework.com/doc-2.0/guide-input-validation.html
     *
     * @return array
     */
    public function rules()
    {
        return [
            ['someAttribute', 'string'],
            ['someAttribute', 'default', 'value' => 'Some Default'],
        ];
    }
}
