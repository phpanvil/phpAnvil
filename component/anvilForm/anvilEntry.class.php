<?php
/**
 * @file
 * @author          Nick Slevkoff <nick@slevkoff.com>
 * @copyright       Copyright (c) 2010 Nick Slevkoff (http://www.slevkoff.com)
 * @license
 *     This source file is subject to the new BSD license that is
 *     bundled with this package in the file LICENSE.txt. It is also
 *     available on the Internet at:  http://www.phpanvil.com/LICENSE.txt
 * @ingroup         phpAnvilTools
 */


require_once PHPANVIL2_COMPONENT_PATH . 'anvilContainer.class.php';

require_once 'anvilEntryType.interface.php';
require_once 'anvilValidationType.interface.php';

require_once 'anvilFormControl.abstract.php';


/**
 * Text Entry Control
 *
 * @version         1.0.2
 * @date            12/21/2010
 * @author          Nick Slevkoff <nick@slevkoff.com>
 * @copyright       Copyright (c) 2010 Nick Slevkoff (http://www.slevkoff.com)
 * @ingroup         phpAnvilTools
 */
class anvilEntry extends anvilFormControlAbstract implements anvilEntryTypeInterface, anvilValidationTypeInterface
{

    const SIZE_DEFAULT = 0;

    const SIZE_LARGE = 2;

    const SIZE_SMALL = 1;

    const VERSION = '1.0.2';

    public $accept;

    /** @var anvilContainer */
    public $append;

    public $appendText;

    public $disabled = false;

    public $length;

    public $maxLength;

    public $onKeyPress;

    public $placeholder;

    /** @var anvilContainer */
    public $prepend;

    public $prependText;

    public $readOnly = false;

    public $size = self::SIZE_DEFAULT;

    public $type = self::ENTRY_TYPE_NORMAL;

    public $validation = false;

    public $value;

    public $wrapClass = 'inputWrap';

    public $wrapEnabled = false;

    private $_sizeClass = array(
        '',
        'input-sm',
        'input-lg'
    );

    private $entryTypeNames = array(
        '',
        'text',
        'password',
        'file',
        'email',
        'number'
    );

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


    public function __construct($id = '', $name = 'unknown', $size = self::SIZE_DEFAULT, $value = '', $properties = null)
    {
        $this->enableLog();

        parent::__construct($id, $name, $properties);

        $this->prepend = new anvilContainer();
        $this->append = new anvilContainer();

        $this->size = $size;
        $this->value = $value;
    }


    public function addValidation($type, $name, $value, $failMessage = '')
    {
        $this->validation = true;
        $this->validationTypes[$name] = $type;
        $this->validationValues[$name] = $value;
        $this->validationFailMessages[$name] = $failMessage;

        if ($type == self::VALIDATION_TYPE_EMAIL) {
            $this->type = self::ENTRY_TYPE_EMAIL;
        } elseif ($type == self::VALIDATION_TYPE_NUMBER) {
            $this->type = self::ENTRY_TYPE_NUMBER;
        }
    }


    public function renderContent()
    {

        $return = '';

        $appendHTML = $this->append->renderContent();
        $prependHTML = $this->prepend->renderContent();

        //---- Render Prepend or Start Append Wrapper --------------------------
        if (!empty($prependHTML) || !empty($this->prependText) || !empty($appendHTML) || !empty($this->appendText)) {

            $return .= '<div class="';
//            if (!empty($appendHTML) || !empty($this->appendText)) {
                $return .= 'input-group';
//            }

//            if (!empty($prependHTML) || !empty($this->prependText)) {
//                $return .= ' input-group';
//            }
            $return .= '">';

            $return .= $prependHTML;

            if (!empty($this->prependText)) {
                $return .= '<span class="input-group-addon">' . $this->prependText . '</span>';
            }
        }


        //---- Render INPUT Tag ------------------------------------------------
        $return .= '<input type="';
        $return .= $this->entryTypeNames[$this->type];
        $return .= '"';

        if ($this->id) {
            $return .= ' id="' . $this->id . '"';
        }

        if ($this->name) {
            $return .= ' name="' . $this->name . '"';
        }

        if ($this->length) {
            $return .= ' size="' . $this->length . '"';
        }

        if ($this->maxLength) {
            $return .= ' maxlength="' . $this->maxLength . '"';
        }

        if ($this->readOnly) {
            $return .= ' readonly="readonly"';
        }

        $return .= ' class="form-control';

        $return .= ' ' . $this->_sizeClass[$this->size];

        if ($this->class) {
            $return .= ' ' . $this->class;
        }

//        if ($this->validation) {
//            $return .= ' anvil-validation';
//
//            if ($this->required) {
//                $return .= ' required';
//            }
//        }


        $return .= '"';

        if ($this->style) {
            $return .= ' style="' . $this->style . '"';
        }

        if ($this->value != '' || ($this->value == 0 && !is_null($this->value))) {
            $return .= ' value="' . str_replace('"', '&quot;', $this->value) . '"';
        } else {
        }

        if ($this->accept) {
            $return .= ' accept="' . $this->accept . '"';
        }

        if (!empty($this->onKeyPress)) {
            $return .= ' onkeypress="' . $this->onKeyPress . '"';
        } else {
            if ($this->defaultButtonID) {
                $return .= ' onkeypress="enterSubmit(event, \'' . $this->defaultButtonID . '\');"';
            }
        }

        if ($this->disabled) {
            $return .= ' disabled="disabled"';
        }

        if ($this->placeholder) {
            $return .= ' placeholder="' . $this->placeholder . '"';
        }

        //---- Render Validation -----------------------------------------------
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

        $return .= ' />';

        if (!empty($appendHTML) || !empty($this->appendText)) {
            if (!empty($this->appendText)) {
                $return .= '<span class="input-group-addon">' . $this->appendText . '</span>';
            }
            $return .= $appendHTML;
//            $return .= '</div>';
        }

        //---- Render Append or Close Prepend Wrapper --------------------------
        if (!empty($prependHTML) || !empty($this->prependText) || !empty($appendHTML) || !empty($this->appendText)) {
            $return .= '</div>';
        }

        if ($this->validation) {
            $return .= '<span class="help-block"></span>';
        }

        return $return;
    }


    public function renderPostClientScript()
    {
        $return = '';
        $return .= parent::renderPostClientScript();

        return $return;
    }


    public function renderPreClientScript()
    {
        $return = '';
        $return .= parent::renderPreClientScript();

        return $return;
    }
}
