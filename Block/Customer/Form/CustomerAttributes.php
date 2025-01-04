<?php
/**
* SMSFunnel | CustomerAttributes.php
* @category SMSFunnel
* @copyright Copyright (c) 2024 SMSFUNNEL - Magento Solution Partner.
* @author SMSFunnel
*/
declare(strict_types=1);

namespace SmsFunnel\SmsFunnel\Block\Customer\Form;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;
use SmsFunnel\SmsFunnel\Block\Widget\Form\Renderer\Element;
use SmsFunnel\SmsFunnel\Block\Widget\Form\Renderer\Fieldset;
use Magento\Eav\Model\Entity\Attribute;
use Magento\Customer\Model\AttributeMetadataDataProvider;
use Magento\Framework\ObjectManagerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Block\Adminhtml\Form;
use Magento\Customer\Model\Session;
use Magento\Framework\DataObjectFactory;

class CustomerAttributes extends Form
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var DataObjectFactory
     */
    private $dataObjectFactory;

    /**
     * @var Element
     */
    private $elementRenderer;

    /**
     * @var AttributeMetadataDataProvider
     */
    private $attributeMetadataDataProvider;

    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var  array
     */
    private $_customerData;

    /**
     * @var Fieldset
     */
    private $fieldsetRenderer;

    /**
     * Attributes constructor.
     *
     * @param Context $context
     * @param Registry $registry
     * @param Session $session
     * @param FormFactory $formFactory
     * @param Fieldset $fieldsetRenderer
     * @param Element $elementRenderer
     * @param ObjectManagerInterface $objectManager
     * @param DataObjectFactory $dataObjectFactory
     * @param AttributeMetadataDataProvider $attributeMetadataDataProvider
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        Session $session,
        FormFactory $formFactory,
        Fieldset $fieldsetRenderer,
        Element $elementRenderer,
        ObjectManagerInterface $objectManager,
        DataObjectFactory $dataObjectFactory,
        AttributeMetadataDataProvider $attributeMetadataDataProvider,
        array $data = []
    ) {
        parent::__construct($context, $registry, $formFactory, $data);
        $this->session           = $session;
        $this->fieldsetRenderer  = $fieldsetRenderer;
        $this->elementRenderer   = $elementRenderer;
        $this->objectManager     = $objectManager;
        $this->storeManager      = $context->getStoreManager();
        $this->dataObjectFactory = $dataObjectFactory;
        $this->attributeMetadataDataProvider = $attributeMetadataDataProvider;
    }

    /**
     * Check whether attribute is visible on front
     *
     * @param Attribute $attribute
     *
     * @return bool
     */
    public function isAttributeVisibleOnFront(Attribute $attribute)
    {
        return $attribute->getIsVisible();
    }

    protected function _prepareForm()
    {
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $type = 'customer_attributes_registration';

        $attributes = $this->attributeMetadataDataProvider->loadAttributesCollection(
            'customer',
            $type
        );

        if (!$attributes || !$attributes->getSize()) {
            return;
        }
        $fieldset = $form->addFieldset(
            'group-fields-customer-attributes',
            [
                'class' => 'user-defined',
                'legend' => __('Additional Information')
            ]
        );
        $fieldset->setRenderer($this->fieldsetRenderer);

        $this->_setFieldset($attributes, $fieldset);
        $this->prepareDependentAttributes($attributes, $fieldset);

        $this->setForm($form);
    }

    /**
     * @inheritdoc
     */
    protected function _initFormValues()
    {
        if ($form = $this->getForm()) {
            if ($this->getCustomerData()) {
                $form->addValues($this->getCustomerData());
            }
            /** @var \Magento\Customer\Block\Form\Register $registerForm */
            $registerForm = $this->getLayout()->getBlock('customer_form_register');
            if (is_object($registerForm) && $registerForm->getFormData() instanceof \Magento\Framework\DataObject) {
                $form->addValues($registerForm->getFormData()->getData());
            }
        }

        return parent::_initFormValues();
    }

    /**
     * Set Fieldset to Form
     *
     * @param array|\Magento\Customer\Model\ResourceModel\Form\Attribute\Collection $attributes
     * $attributes - attributes that will be added
     * @param \Magento\Framework\Data\Form\Element\Fieldset $fieldset
     * @param array $exclude
     * @return void
     */
    protected function _setFieldset($attributes, $fieldset, $exclude = [])
    {
        $this->_addElementTypes($fieldset);

        foreach ($attributes as $attribute) {
            /** @var $attribute \Magento\Eav\Model\Entity\Attribute */
            if (!$this->isAttributeVisibleOnFront($attribute)) {
                continue;
            }
            $attribute->setStoreId($this->storeManager->getStore()->getId());

            if ($inputType = $attribute->getFrontend()->getInputType()) {
                $rendererClass = $attribute->getFrontend()->getInputRendererClass();
                
                if ($inputType == "boolean") {
                    $fieldType = 'SmsFunnel\SmsFunnel\Block\Data\Form\Element\\' . ucfirst($inputType);
                } else {
                    $fieldType = 'Magento\Framework\Data\Form\Element\\' . ucfirst($inputType);
                }
                
                if (!empty($rendererClass)) {
                    $fieldType = $inputType . '_' . $attribute->getAttributeCode();
                    $fieldset->addType($fieldType, $rendererClass);
                }

                $data = [
                    'name' => $attribute->getAttributeCode(),
                    'label' => $attribute->getStoreLabel(),
                    'class' => $attribute->getFrontend()->getClass(),
                    'required' => $attribute->getIsRequired() || $attribute->getRequiredOnFront(),
                    'note' => $attribute->getNote()
                ];
               
                $element = $fieldset->addField(
                    $attribute->getAttributeCode(),
                    $fieldType,
                    $data
                )->setEntityAttribute(
                    $attribute
                );

                $element->setValue($attribute->getDefaultValue());
                $element->setRenderer($this->elementRenderer);
                $element->setAfterElementHtml($this->_getAdditionalElementHtml($element));

                /* add options format */
                $this->_applyTypeSpecificConfig($inputType, $element, $attribute);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function _applyTypeSpecificConfig($inputType, $element, Attribute $attribute)
    {
        switch ($inputType) {
            case 'select':
                $element->addElementValues($attribute->getSource()->getAllOptions(true, false));
                break;
            case 'multiselect':
                $element->addElementValues($attribute->getSource()->getAllOptions(false, false));
                $element->setCanBeEmpty(true);
                break;
            default:
                break;
        }
    }

    /**
     * @param \Magento\Customer\Model\ResourceModel\Form\Attribute\Collection $attributes
     * @param \Magento\Framework\Data\Form\Element\Fieldset $fieldset
     */
    protected function prepareDependentAttributes($attributes, $fieldset)
    {
        $depends = [];
        $attributeIds = $attributes->getColumnValues('attribute_id');
        if (empty($attributeIds)) {
            return;
        }
       
        //you can add array options dynamically as per your need
        $depends[] = [
            'parent_option_id' => 1,
            'depend_attribute_id' => 162,
            'depend_attribute_code' => 'phone'
        ];
      
        if (!empty($depends)) {
            $fieldset->setData('depends', $depends);
        }
    }

    /**
     * @return array
     */
    private function getCustomerData()
    {
        if (!isset($this->_customerData)) {
            $this->_customerData = [];
            if ($this->session->isLoggedIn()) {
                $this->_customerData = $this->session->getCustomer()->getData();
            }
        }

        return $this->_customerData;
    }
}
