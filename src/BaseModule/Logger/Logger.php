<?php
namespace BaseModule\Logger;

use SimpleCollection\EntityInterface;

/**
 * Logger to log something
 *
 * @copyright Felix Buchheim
 * @author    Felix Buchheim <hanibal4nothing@gmail.com>
 */
class Logger extends \Zend\Log\Logger implements EntityInterface
{
    /**
     * Priority of this logger
     *
     * @var int
     */
    protected $priority;

    /**
     * Logger constructor.
     *
     * @param int $iPriority
     * @param null                    $aOptions
     */
    public function __construct($iPriority, $aOptions = null)
    {
        $this->setPriority($iPriority);
        parent::__construct($aOptions);
    }

    /**
     * The getter function for the property <em>$iPriority</em>.
     *
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * The setter function for the property <em>$iPriority</em>.
     *
     * @param  int $iPriority
     *
     * @return $this Returns the instance of this class.
     */
    public function setPriority($iPriority)
    {
        $this->priority = $iPriority;

        return $this;
    }

    /**
     * Dummy function, dont use this
     *
     * @deprecated
     *
     * @return array
     */
    public function toArray()
    {
        return array();
    }
}