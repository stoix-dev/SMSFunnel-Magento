<?php
/**
* SMSFunnel | Boolean.php
* @category SMSFunnel
* @copyright Copyright (c) 2024 SMSFUNNEL - Magento Solution Partner.
* @author SMSFunnel
* @Support Leonardo Menezes - suporte@smsfunnel.com.br
*/
declare(strict_types=1);

namespace SmsFunnel\SmsFunnel\Block\Data\Form\Element;
class Boolean extends \Magento\Catalog\Block\Adminhtml\Product\Helper\Form\Boolean
{
    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $values = $this->getValues();
        if (!$this->getRequired()) {
            array_unshift($values, ['label' => ' ', 'value' => '']);
        }
        $this->setValues($values);
    }
}
