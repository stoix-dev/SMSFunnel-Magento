<?php
/**
* SMSFunnel | Fieldset.php
* @category SMSFunnel
* @copyright Copyright (c) 2024 SMSFUNNEL - Magento Solution Partner.
* @author SMSFunnel
* @Support Leonardo Menezes - suporte@smsfunnel.com.br
*/
declare(strict_types=1);

namespace SmsFunnel\SmsFunnel\Block\Widget\Form\Renderer;

class Fieldset extends \Magento\Backend\Block\Widget\Form\Renderer\Fieldset
{
    protected $template = 'SmsFunnel_SmsFunnel::widget/form/renderer/fieldset.phtml';

    public function getRelationJson()
    {
        $depends = $this->getElement()->getData('depends');
        if (!$depends) {
            return '';
        }
        foreach ($depends as &$relation) {
            $relation['parent_attribute_element_uid'] = $this->getJsId(
                'form-field',
                $relation['parent_attribute_code']
            );
            $relation['depend_attribute_element_uid'] = $this->getJsId(
                'form-field',
                $relation['depend_attribute_code']
            );
        }
        $this->getElement()->setData('depends', $depends);

        return $this->getElement()->convertToJson(['depends']);
    }
}
