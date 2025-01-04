define([
    'jquery'
], function($) {
        'use strict';
        return {
            config : {},
            indexedElements : [],
            /**
             * @param {Object[]} options
             * @returns {jquery}
             */
            init: function (options) {
                this.config = options.depends;
                this.initInputElements();
                return this;
            },
            // init parent element listeners
            initInputElements: function() {
                var different = [];
                $.each(this.config, function(key, parentrelation) {
                    var element = this.getElement(parentrelation.parent_attribute_element_uid);
                    if (element != void(0) && element.length && $.inArray(element.selector, different) == -1) {
                        different.push(element.selector);
                        element.on('change', function (event) {
                            this.observer(event);
                            this.indexedElements = [];
                        }.bind(this));
                        // for custom check
                        element.on('check_relations', this.observer.bind(this));
                        element.find('select').each(function (key, input) {
                            $(input).trigger("check_relations");
                        });
                        this.indexedElements = [];
                    }
                }.bind(this));
            },
            getElement: function (id) {
                return $('[data-ui-id="' + id + '"]');
            },
            observer: function (event) {
                var element = $(event.target);
                var block = $(event.currentTarget);
                if (element && block) {
                    var elementId = block.attr('data-ui-id');
                    this.checkDependencies(element, elementId);
                }
            },
            checkDependencies: function (element, elementId) {
                // Find dependents elements
                var elementDependencies = this.findElementDependencies(elementId);
                // Iterate throw elements and show required elements
                $.each(elementDependencies, function(key, relation) {
                    if (this.getElement(relation.depend_id).length) {
                            // Multiselect and select
                        if (this.canShow(relation)) {
                            this.showBlock(relation.depend_id);
                        } else if (this.indexedElements.indexOf(relation.depend_id) < 0) {
                            this.hideBlock(relation.depend_id);
                        }

                    }
                }.bind(this));
            },
            canShow: function (relationToShow) {
                var parentRelations = this.findElementParentDependencies(relationToShow);
                var result = true;

                // check all parent elements
                $.each(parentRelations, function(key, relation) {
                    var block = this.getElement(relation.parent_id);
                    if (result && block.length) {
                        result = !!(this.checkSelect(block.find('select'), relation));
                    }
                }.bind(this));

                return result;
            },
           
            /*
             * check for select, multiselect
             */
            checkSelect: function (element, relation) {
                if (!element.length) {
                    return false;
                }
                return element.val() != void(0) && element.val().indexOf(relation.value) != -1 && element.is(":visible")
            },

            hideBlock: function (id) {
                var element = this.getElement(id);
                element.hide();
                element.find('select').each(function (key, input) {
                    $(input).trigger("check_relations");
                });
            },
            showBlock: function (id) {
                var element = this.getElement(id);
                element.show();
                this.indexedElements.push(id);
                element.find('select').each(function (key, input) {
                    $(input).trigger("check_relations");
                });
            },
            findElementDependencies: function (elementUId) {
                var elements = [];
                $.each(this.config, function(key, item) {
                    if (item.parent_attribute_element_uid == elementUId) {
                        var el = {
                            'depend_id': item.depend_attribute_element_uid,
                            'value': item.parent_option_id
                        };
                        elements.push(el);
                    }
                });
                return elements;
            },
            findElementParentDependencies: function (elementId) {
                var elements = [];
                $.each(this.config, function(key, item) {
                    if (item.depend_attribute_element_uid == elementId.depend_id
                        && item.parent_option_id == elementId.value
                    ) {
                        var el = {
                            'parent_id': item.parent_attribute_element_uid,
                            'depend_id': item.depend_attribute_element_uid,
                            'value': item.parent_option_id
                        };
                        elements.push(el);
                    }
                });
                return elements;
            }
        }
    }
);
