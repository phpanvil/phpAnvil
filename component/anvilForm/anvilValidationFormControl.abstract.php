<?php

require_once 'anvilValidationType.interface.php';

require_once 'anvilFormControl.abstract.php';


abstract class anvilValidationFormControlAbstract extends anvilFormControlAbstract implements anvilValidationTypeInterface
{

    public $validation = false;

    private $validationFailMessages = array();

    private $validationTypeNames = array(
        '',
        'email',
        'number',
        'required',
        'max',
        'min',
        'maxlength',
        'minlength',
        'pattern',
        'match',
        'maxchecked',
        'minchecked',
        'regex',
        'callback',
        'ajax'
    );

    private $validationTypes = array();

    private $validationValues = array();


    public function addValidation($type, $name, $value, $failMessage = '')
    {
        $this->validation = true;
        $this->validationTypes[$name] = $type;
        $this->validationValues[$name] = $value;
        $this->validationFailMessages[$name] = $failMessage;
    }

    protected function renderValidationParameters()
    {
        $return = '';

        if ($this->validation) {
            foreach ($this->validationTypes as $name => $type) {
                switch ($type) {
                    case self::VALIDATION_TYPE_EMAIL:
                    case self::VALIDATION_TYPE_NUMBER:
                        break;

                    case self::VALIDATION_TYPE_REQUIRED:
                        $return .= ' required';
                        break;

                    case self::VALIDATION_TYPE_MAX:
                    case self::VALIDATION_TYPE_MIN:
                    case self::VALIDATION_TYPE_MAX_LENGTH:
                    case self::VALIDATION_TYPE_MIN_LENGTH:
                    case self::VALIDATION_TYPE_PATTERN:

                        $return .= ' ' . $this->validationTypeNames[$type];
                        $return .= '="' . $this->validationValues[$name] . '"';
                        break;

                    case self::VALIDATION_TYPE_MATCH:
                    case self::VALIDATION_TYPE_MAX_CHECKED:
                    case self::VALIDATION_TYPE_MIN_CHECKED:
                    case self::VALIDATION_TYPE_REGEX:
                    case self::VALIDATION_TYPE_CALLBACK:
                    case self::VALIDATION_TYPE_AJAX:

                        $return .= ' data-validation-' . $name . '-' . $this->validationTypeNames[$type];
                        $return .= '="' . $this->validationValues[$name] . '"';
                        break;
                }

                if (!empty($this->validationFailMessages[$name])) {
                    $return .= ' data-validation-' . $name . '-message';
                    $return .= '="' . $this->validationFailMessages[$name] . '"';
                    break;
                }
            }
        }

        return $return;
    }

}
