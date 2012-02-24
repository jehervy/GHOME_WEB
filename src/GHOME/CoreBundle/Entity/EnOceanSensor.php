<?php

namespace GHOME\CoreBundle\Entity;

/**
 * A implementation of a sensor using EnOcean.
 */
class EnOceanSensor
{
    private $virtualId;
	private $physicalId;
	private $valid;
	private $dataType;
	private $dataPos;
	private $dataLength;
	private $dataValues = array();
	
	private static $types = array(
	   'binary' => 'Binaire',
	   'numeric' => 'Numérique',
	);
	
	public static function getTypes()
	{
	    return self::$types;
	}

    /**
     * Get virtual id
     *
     * @return integer 
     */
    public function getVirtualId()
    {
        return $this->virtualId;
    }
    
    /**
     * Set virtual id
     *
     * @return integer 
     */
    public function setVirtualId($virtualId)
    {
        $this->virtualId = $virtualId;
    }
    
    /**
     * Get physical id
     *
     * @return integer
     */
    public function getPhysicalId()
    {
        return $this->physicalId;
    }
    
    /**
     * Set physical id
     *
     * @return integer
     */
    public function setPhysicalId($physicalId)
    {
        $this->physicalId = $physicalId;
    }
    
    /**
     * Get valid
     *
     * @return integer
     */
    public function getValid()
    {
        return $this->valid;
    }
    
    /**
     * Set valid
     *
     * @return integer
     */
    public function setValid($valid)
    {
        $this->valid = $valid;
    }
    
    /**
     * Get data type
     *
     * @return string
     */
    public function getDataType()
    {
        return $this->dataType;
    }
    
    /**
     * Set data type
     *
     * @return string
     */
    public function setDataType($dataType)
    {
        $this->dataType = $dataType;
    }
    
    /**
     * Get data position
     *
     * @return integer
     */
    public function getDataPos()
    {
        return $this->dataPos;
    }
    
    /**
     * Set data position
     *
     * @return integer
     */
    public function setDataPos($dataPos)
    {
        $this->dataPos = $dataPos;
    }
    
    /**
     * Get data length
     *
     * @return integer
     */
    public function getDataLength()
    {
        return $this->dataLength;
    }
    
    /**
     * Set data length
     *
     * @return integer
     */
    public function setDataLength($dataLength)
    {
        $this->dataLength = $dataLength;
    }
    
    /**
     * Get data min value
     *
     * @return integer
     */
    public function getDataMin()
    {
        return $this->dataValues['min'];
    }
    
    /**
     * Set data min value
     *
     * @return integer
     */
    public function setDataMin($dataMin)
    {
        $this->dataValues['min'] = $dataMin;
    }
    
    /**
     * Get data max value
     *
     * @return integer
     */
    public function getDataMax()
    {
        return $this->dataValues['max'];
    }
    
    /**
     * Set data max value
     *
     * @return integer
     */
    public function setDataMax($dataMax)
    {
        $this->dataValues['max'] = $dataMax;
    }
    
    /**
     * Get data max value
     *
     * @return array
     */
    public function getDataValues()
    {
        return $this->dataValues;
    }
    
    /**
     * Set data max value
     *
     * @return integer
     */
    public function addDataValue($binary ,$real)
    {
        $this->dataValues[$binary] = $real;
    }
}