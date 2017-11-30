<?php

/**
 * XML-persisted collection.
 * 
 * @author		JLP
 * @copyright           Copyright (c) 2010-2017, James L. Parry
 * ------------------------------------------------------------------------
 */
class XML_Model extends Memory_Model
{
    private $_set;
    private $_record;
//---------------------------------------------------------------------------
//  Housekeeping methods
//---------------------------------------------------------------------------

	/**
	 * Constructor.
	 * @param string $origin Filename of the CSV file
	 * @param string $keyfield  Name of the primary key field
	 * @param string $entity	Entity name meaningful to the persistence
     * @param string $tag  the tag name for an sigle record in the xml file 
	 */
	function __construct($origin = null, $keyfield = 'id', $entity = null)
	{
		parent::__construct();

		// guess at persistent name if not specified
		if ($origin == null)
			$this->_origin = get_class($this);
		else
			$this->_origin = $origin;

		// remember the other constructor fields
		$this->_keyfield = $keyfield;
		$this->_entity = $entity;
        $this->_set = lcfirst(get_class($this)); //defaut value, overrided in load function
        $this->_record = $this->_set . '_item'; //defaut value, overrided in load function

		// start with an empty collection
		$this->_data = array(); // an array of objects
		$this->fields = array(); // an array of strings
		// and populate the collection
		$this->load();
	}

	/**
	 * Load the collection state appropriately, depending on persistence choice.
	 * OVER-RIDE THIS METHOD in persistence choice implementations
	 */
	protected function load()
	{
        $doc = new DOMDocument();

        $doc->preserveWhiteSpace = false; // otherwise the whitespaces will generate "#text" nodes in the node list

        $doc->load($this->_origin);

        $root = $doc->documentElement;
        $this->_set = $root->tagName;

        if (!$root->hasChildNodes())
            return;

        $first = $root->firstChild;
        $this->_record = $first->tagName;

        //var_dump($this->_set);
        //var_dump($this->_record);

        foreach ($first->childNodes as $property)
        {
            $this->_fields []= $property->tagName;
        }

        //var_dump($this->_fields);

        foreach ($root->childNodes as $item)
        {
            $record = new stdClass();
            foreach ($item->childNodes as $property)
            {
                //var_dump($property->tagName);
                //var_dump($property->nodeValue);
                $record->{$property->tagName} = $property->nodeValue;
            } 

            $this->_data []= $record;
        }

        //var_dump($this->_data);

		$this->reindex();
	}

	/**
	 * Store the collection state appropriately, depending on persistence choice.
	 * OVER-RIDE THIS METHOD in persistence choice implementations
	 */
	protected function store()
	{
		// rebuild the keys table
		$this->reindex();
        $doc = new DOMDocument('1.0', 'utf-8');
        $doc->formatOutput = true;

        $root = $doc->createElement($this->_set); 

        foreach ($this->_data as $record)
        {
            foreach ($this->_data as $record)
            {
                $item = $doc->createElement($this->_record);
                foreach ($record as $key => $value) 
                {
                    //var_dump($key, $value);
                    $property = $doc->createElement($key, $value);
                    $item->appendChild($property);
                }
                $root->appendChild($item);
            }
            //var_dump($root);
        }
        $doc->appendChild($root);

        //var_dump($doc->saveXML());
        $doc->save($this->_origin);

		//---------------------
        /*
		if (($handle = fopen($this->_origin, "w")) !== FALSE)
		{
			fputcsv($handle, $this->_fields);
			foreach ($this->_data as $key => $record)
				fputcsv($handle, array_values((array) $record));
			fclose($handle);
		}
         */
		// --------------------
	}
}
