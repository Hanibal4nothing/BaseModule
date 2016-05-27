<?php
namespace BaseModule\Logger;

use SimpleCollection\AbstractCollection;
use SimpleCollection\EntityInterface;

/**
 * Collection with different Loggers
 *
 * @copyright Felix Buchheim
 * @author    Felix Buchheim <hanibal4nothing@gmail.com>
 */
class LoggerCollection extends AbstractCollection
{
    /**
     * Logger to write the logs
     *
     * @var Logger[]
     */
    public $entities = array();

    /**
     * @param EntityInterface $oEntity
     *
     * @return $this
     */
    public function add(EntityInterface $oEntity)
    {
        /* @var Logger $oEntity */
        $this->entities[$oEntity->getPriority()] = $oEntity;

        return $this;
    }
}