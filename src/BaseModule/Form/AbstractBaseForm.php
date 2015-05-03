<?php
/**
 * AbstractBaseForm.php
 *
 * @copyright Felix Buchheim
 * @version   $Id: $
 */

namespace BaseModule\Form;

use Zend\Form\Form;

/**
 * @package   BaseModule\Form
 * @copyright Felix Buchheim
 * @author    Felix Buchheim <hanibal4nothing@gmail.com>
 * @version   $Id: $
 */
abstract class AbstractBaseForm extends Form
{
    /**
     * Return a single Error per Element and not all
     *
     * @return array
     */
    public function getSingleErrorMsgPerElement()
    {
        $aErrors = array();
        foreach ($this->getMessages() as $sElementName => $aElement) {
            $aErrors[$sElementName] = reset($aElement);
        }

        return $aErrors;
    }

    /**
     * Return a string for all valid-errors
     *
     * @return string
     */
    public function getErrorMessagesAsString()
    {
        $sMessage = '';
        foreach($this->getMessages() as $sElementName => $aElement){
            foreach($aElement as $sMessage){
                $sMessage .= $sElementName . ': ' . $sMessage . PHP_EOL;
            }
        }

        return $sMessage;
    }
} 