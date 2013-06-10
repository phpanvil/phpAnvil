<?php

require_once PHPANVIL2_FRAMEWORK_PATH . 'anvilRSModel.abstract.php';

/**
 * @property string $dts
 * @property int    $fuseEventTypeID
 * @property int    $fuseApplicationID
 * @property string $version
 * @property string $userIP
 * @property int    $userID
 * @property string $name
 * @property int    $number
 * @property string $details
 * @property string $file
 * @property int    $line
 * @property string $trace
 */
class FuseEventModel extends anvilRSModelAbstract
{
    public function __construct($tableName = 'fuse_events', $id = 0)
    {

        $this->_saveActivity = false;

        parent::__construct($tableName, 'fuse_event_id');

        $this->fields->id->fieldName = 'fuse_event_id';
        $this->fields->id->fieldType = anvilModelField::DATA_TYPE_INTEGER;

        $this->fields->dts->fieldName = 'dts';
        $this->fields->dts->fieldType = anvilModelField::DATA_TYPE_DTS;

        $this->fields->fuseEventTypeID->fieldName = 'fuse_event_type_id';
        $this->fields->fuseEventTypeID->fieldType = anvilModelField::DATA_TYPE_INTEGER;

        $this->fields->fuseApplicationID->fieldName = 'fuse_application_id';
        $this->fields->fuseApplicationID->fieldType = anvilModelField::DATA_TYPE_INTEGER;

        $this->fields->version->fieldName = 'version';
        $this->fields->version->fieldType = anvilModelField::DATA_TYPE_NUMERIC_STRING;
        $this->fields->version->maxLength = 10;

        $this->fields->userIP->fieldName = 'user_ip';
        $this->fields->userIP->fieldType = anvilModelField::DATA_TYPE_NUMERIC_STRING;
        $this->fields->userIP->maxLength = 20;

        $this->fields->userID->fieldName = 'user_id';
        $this->fields->userID->fieldType = anvilModelField::DATA_TYPE_INTEGER;

        $this->fields->name->fieldName = 'name';
        $this->fields->name->fieldType = anvilModelField::DATA_TYPE_ALPHA_NUMERIC_STRING;
        $this->fields->name->maxLength = 255;

        $this->fields->number->fieldName = 'number';
        $this->fields->number->fieldType = anvilModelField::DATA_TYPE_INTEGER;

        $this->fields->details->fieldName = 'details';
        $this->fields->details->fieldType = anvilModelField::DATA_TYPE_HTML;

        $this->fields->file->fieldName = 'file';
        $this->fields->file->fieldType = anvilModelField::DATA_TYPE_FILE_PATH;
        $this->fields->file->maxLength = 128;

        $this->fields->line->fieldName = 'line';
        $this->fields->line->fieldType = anvilModelField::DATA_TYPE_INTEGER;

        $this->fields->trace->fieldName = 'trace';
        $this->fields->trace->fieldType = anvilModelField::DATA_TYPE_HTML;

        $this->id = $id;
    }
}

?>
