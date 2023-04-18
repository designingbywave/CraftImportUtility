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
 * Entry Model
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
class Entry extends Model
{
    // Public Properties
    // =========================================================================

    public $last_name;
    public $diploma_name;
    public $division;
    public $degree;
    public $degree_type;
    public $major_one;
    public $major_two;
    public $city;
    public $state;
    public $citizen;

    public $combined_personal_info;

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
    function __construct($last_name,$name,$div,$degree,$type,$major_one,$major_two,$city,$state,$citizen) {
        // This is initializing the class properties
        $this->last_name=$last_name;
        $this->diploma_name=$name;
        $this->division=$div;
        $this->degree=$degree;
        $this->degree_type=$type;
        $this->major_one=$major_one;
        $this->major_two=$major_two;
        $this->city=$city;
        $this->state=$state;
        $this->citizen=$citizen;
    }  
    
    /*
    public function rules()
    {
        return [
            ['someAttribute', 'string'],
            ['someAttribute', 'default', 'value' => 'Some Default'],
        ];
    }
    */

}
