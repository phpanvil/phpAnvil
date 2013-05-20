<?php
require_once('anvilFormControl.abstract.php');


/**
* Combo Box
*
* @copyright 	Copyright (c) 2010-2012 Nick Slevkoff (http://www.slevkoff.com)
*/
class anvilComboBox extends anvilFormControlAbstract {

	const VERSION        	= '1.0';

    private $_sizeClass = array(
        'spanAuto',
        'input-mini',
        'input-small',
        'input-medium',
        'input-large',
        'input-xlarge',
        'input-xxlarge',
        'span1',
        'span2',
        'span3',
        'span4',
        'span5',
        'span6',
        'span7',
        'span8',
        'span9',
        'span10',
        'span11',
        'span12'
    );

    const SIZE_AUTO = 0;
    const SIZE_MINI    = 1;
    const SIZE_SMALL   = 2;
    const SIZE_MEDIUM  = 3;
    const SIZE_LARGE   = 4;
    const SIZE_XLARGE  = 5;
    const SIZE_XXLARGE = 6;
    const SIZE_SPAN1   = 7;
    const SIZE_SPAN2   = 8;
    const SIZE_SPAN3   = 9;
    const SIZE_SPAN4   = 10;
    const SIZE_SPAN5   = 11;
    const SIZE_SPAN6   = 12;
    const SIZE_SPAN7   = 13;
    const SIZE_SPAN8   = 14;
    const SIZE_SPAN9   = 15;
    const SIZE_SPAN10  = 16;
    const SIZE_SPAN11  = 17;
    const SIZE_SPAN12  = 18;

    protected $_preItems = array();
	protected $_postItems = array();
	public $recordset;


	public $dataValue = 'id';
	public $dataName = 'name';
	public $directory;
    public $directoryRegEx;
	public $postbackEnabled = false;
    public $size;
    public $value;
//    public $wrapEnabled = false;

    //---- Validation Properties
    public $validation = true;
    public $required = false;


    public function __construct($id = '', $name = '', $size = self::SIZE_MEDIUM, $value = '', $properties = array()) {
//		$this->_traceEnabled = $traceEnabled;

//        $this->enableLog();

//		unset($this->dataValue);
//		unset($this->dataName);
//		unset($this->directory);
//        unset($this->directoryRegEx);
//		unset($this->postbackEnabled);
//		unset($this->value);
//        unset($this->wrapEnabled);


//		$this->addProperty('dataValue', 'id');
//		$this->addProperty('dataName', 'name');
//		$this->addProperty('directory', null);
//        $this->addProperty('directoryRegEx', null);
//		$this->addProperty('postbackEnabled', false);
//		$this->addProperty('recordset', null);
//		$this->addProperty('value', '');
//        $this->addProperty('wrapEnabled', false);
//        $this->addProperty('wrapClass', 'selectWrap');

        $this->size = $size;
		$this->value = $value;

		parent::__construct($id, $name, $properties);
	}

	public function addPreItem($value, $name) {
		$this->_preItems[$value] = $name;
	}

	public function addPostItem($value, $name) {
		$this->_postItems[$value] = $name;
	}


	public function renderContent() {
//		$this->_addTraceInfo(__FILE__, __METHOD__, __LINE__, 'rendering...', self::TRACE_TYPE_DEBUG);

        $return = '';

//        if ($this->wrapEnabled) {
//            $return .= '<p class="' . $this->wrapClass . '">';
//        }

        $return .= $this->renderLabel();

		#---- Render the Combo Box Starting Tag
		$return .= '<select';


        //---- ID
		if ($this->id) {
			$return .= ' id="' . $this->id . '"';
		}

        //---- Name
		if ($this->name) {
			$return .= ' name="' . $this->name . '"';
		}

        //---- Class
        $return .= ' class="' . $this->_sizeClass[$this->size];

        if ($this->class) {
            $return .= ' ' . $this->class;
        }

        if ($this->validation) {
            $return .= ' anvil-validation';

            if ($this->required) {
                $return .= ' required';
            }
        }

        $return .= '"';

        //---- Style
        if ($this->style) {
            $return .= ' style="' . $this->style . '"';
        }


//		$triggers = $this->renderTriggers();

//		$this->_addTraceInfo(__FILE__, __METHOD__, __LINE__, $triggers, self::TRACE_TYPE_DEBUG);

//		$return .= $this->renderTriggers();
//		$return .= $triggers;



/*
		if ($this->_enableAjax) {
			$return .= ' onChange="call_' . $this->_name . '();"';
		} elseif ($this->_enablePostBack) {
			$return .= " onChange=\"this.form.pbf.value='" . $this->_name . "';this.form.submit();\"";
		} elseif (isset($this->_lookupURL)) {
			$return .= ' onChange="call_' . $this->_name . '();"';
		} elseif (isset($this->_onChange)) {
			$return .= ' onChange="' . $this->_onChange . '"';
		}
*/

//		if ($this->onClick) {
//			$return .= ' onClick="' . $this->onClick . '"';
//		}

//		if ($this->defaultButtonID) {
//			$return .= ' onkeypress="enterSubmit(event, \'' . $this->defaultButtonID . '\');"';
//		}

		$return .= '>';

		#---- Render Pre Added Items
        $preItemCount = count($this->_preItems);

//        FB::log($this->name,'Name');
//        FB::log($preItemCount,'$preItemCount');

        reset($this->_preItems);
		for ($i = 0; $i < $preItemCount; $i++)
		{
			$return .= '<option value="' . key($this->_preItems) . '"';

			if ($this->value == key($this->_preItems)) {
				$return .= ' selected="selected"';
			}


			$return .= '>' . $this->_preItems[key($this->_preItems)] . "</option>\n";


			next($this->_preItems);
		}

//        FB::log($return, '$return');

//        fb::log($this->name, 'ComboBox Name');
//        fb::log($this->recordset, 'Recordset');

		#---- Render the ComboBox Items	from SQL Data
		if ($this->recordset) {
//			DevTrace::add(__FILE__, __METHOD__, __LINE__, '[' . $this->_id . '] Processing SQL...');

//			$objRS = $this->_devData->execute($this->_sql);

			$objRS = $this->recordset;

			if ($objRS->read()) {
				do {
					/*
					for($objRS->columns->moveFirst(); $objRS->columns->hasMore(); $objRS->columns->moveNext()) {
						$objColumn = $objRS->columns->current();
						if (!array_key_exists($objColumn->name, $this->_hideColumn)) {
							$return .= '<td class="' . $this->_cellClass . '">' . $objRS->data($objRS->columns->getIndex()) . '</td>';
						}
					}
					$return .= '</tr>';
					*/

					$return .= '<option value="' . $objRS->data($this->dataValue) . '"';

					if ($this->value == $objRS->data($this->dataValue)) {
						$return .= ' selected="selected"';
					}


					$return .= '>' . $objRS->data($this->dataName, DATA_TYPE_STRING) . '</option>' . PHP_EOL;

				} while($objRS->read());

				$objRS->close();

			}

		} elseif ($this->directory) {
//			DevTrace::add(__FILE__, __METHOD__, __LINE__, '[' . $this->_id . '] Processing Directory Files...');
//			DevTrace::add(__FILE__, __METHOD__, __LINE__, '[' . $this->_id . '] $this->_dir=' . $this->_dir, self::TRACE_TYPE_DEBUG);
//			DevTrace::add(__FILE__, __METHOD__, __LINE__, '[' . $this->_id . '] realpath=' . realpath($this->_dir), self::TRACE_TYPE_DEBUG);
//			DevTrace::add(__FILE__, __METHOD__, __LINE__, '[' . $this->_id . '] dirname=' . dirname($this->_dir), self::TRACE_TYPE_DEBUG);

			if ($handle = opendir($this->directory))
            {
                $filterFiles = isset($this->directoryRegEx);
                $files = array();

//				while (false !== ($file = readdir($handle)))
//                {
//					if ($file != '.' && $file != '..')
//                    {
//                        if (!$filterFiles || preg_match($this->directoryRegEx, $file) > 0)
//                        {
//						    $return .= '<option value="' . $file . '"';

//						    if ($this->value == $file) {
//							    $return .= ' selected="selected"';
//						    }

//						    $return .= '>' . $file . "</option>\n";
//                        }
//					}
//				}
//				closedir($handle);

                while (false !== ($file = readdir($handle)))
                {
                    if ($file != '.' && $file != '..')
                    {
                        if (!$filterFiles || preg_match($this->directoryRegEx, $file) > 0)
                        {
                            $files[] = $file;
                        }
                    }
                }
                closedir($handle);
                sort($files);

                $count = count($files);
                for ($i = 0; $i < $count; $i++)
                {
                    $return .= '<option value="' . $files[$i] . '"';

                    if ($this->value == $files[$i]) {
                        $return .= ' selected="selected"';
                    }

                    $return .= '>' . $files[$i] . '</option>' . PHP_EOL;
                }
            }

		}

		#---- Render Post Added Items
		for ($i = 0; $i < count($this->_postItems); $i++)
		{
			$return .= '<option value="' . key($this->_postItems) . '"';

			if ($this->_selectedID == key($this->_postItems)) {
				$return .= ' selected="selected"';
			}


			$return .= '>' . $this->_postItems[key($this->_postItems)] . '</option>' . PHP_EOL;


			next($this->_postItems);
		}

		#---- Render the Combo Box Ending Tag
		$return .= '</select>' . PHP_EOL;

        //---- Render Validation Placeholder -----------------------------------
        if ($this->validation && $this->required) {
            $return .= '<span class="help-validation">';
            $return .= '<span class="label"></span>';
            $return .= '<span class="description"></span>';
            $return .= '</span>';
        }

//        fb::log($return);
//        if ($this->wrapEnabled) {
//            $return .= '</p>';
//        }

		return $return;
	}

}

?>