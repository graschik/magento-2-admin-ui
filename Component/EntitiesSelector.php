<?php
/**
 * The MIT License (MIT)
 *
 * Copyright (c) Aliaksandr Hrashchanka
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * <https://opensource.org/licenses/MIT>.
 */
declare(strict_types=1);

namespace Grasch\AdminUi\Component;

use Grasch\AdminUi\Model\UiComponentGenerator;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Ui\Component\AbstractComponent;
use Magento\Ui\Component\DynamicRows;
use Magento\Ui\Component\Form\Element\DataType\Number;
use Magento\Ui\Component\Form\Element\DataType\Text;
use Magento\Ui\Component\Form\Element\Input;
use Magento\Ui\Component\Form\Field;
use Magento\Ui\Component\Modal;

class EntitiesSelector extends AbstractComponent
{
    public const NAME = 'entitiesSelector';

    protected const COLUMN_FIELD_ORIGINAL_NAME = 'original_name';
    protected const COLUMN_FIELD_TYPE          = 'type';
    protected const COLUMN_FIELD_LABEL         = 'label';
    protected const COLUMN_FIELD_SORT_ORDER    = 'sortOrder';
    protected const COLUMN_FIELD_FIT           = 'fit';

    /**
     * @var UrlInterface
     */
    protected UrlInterface $urlBuilder;

    /**
     * @var UiComponentGenerator
     */
    protected UiComponentGenerator $uiComponentGenerator;

    /**
     * @var array|string[]
     */
    protected array $_requiredColumnFields = [
        self::COLUMN_FIELD_ORIGINAL_NAME,
        self::COLUMN_FIELD_TYPE,
        self::COLUMN_FIELD_LABEL,
        self::COLUMN_FIELD_SORT_ORDER,
        self::COLUMN_FIELD_FIT
    ];

    /**
     * @var array
     */
    protected array $_map = [];

    /**
     * @var array
     */
    protected array $_columnsConfig = [];

    /**
     * @param ContextInterface $context
     * @param UrlInterface $urlBuilder
     * @param UiComponentGenerator $uiComponentGenerator
     * @param array $components
     * @param array $data
     * @param array $requiredColumnFields
     * @throws LocalizedException
     */
    public function __construct(
        ContextInterface $context,
        UrlInterface $urlBuilder,
        UiComponentGenerator $uiComponentGenerator,
        array $components = [],
        array $data = [],
        array $requiredColumnFields = []
    ) {
        parent::__construct(
            $context,
            $components,
            $data
        );

        $this->urlBuilder = $urlBuilder;
        $this->uiComponentGenerator = $uiComponentGenerator;

        $this->_requiredColumnFields = array_merge(
            $this->_requiredColumnFields,
            $requiredColumnFields
        );

        $this->_initColumnsConfig();
    }

    /**
     * Init columns config
     *
     * @throws LocalizedException
     */
    protected function _initColumnsConfig()
    {
        $columns = $this->getRequiredConfigValueByPath('grid/columns');
        if (empty($columns)) {
            throw new LocalizedException(__(
                '"config/grid/columns" can not be empty for the "%2" field.',
                $this->getName()
            ));
        }

        foreach ($columns as $columnName => $columnConfig) {
            foreach ($this->_requiredColumnFields as $requiredField) {
                $this->getRequiredConfigValueByPath(
                    'grid/columns/' . $columnName . '/' . $requiredField
                );
            }

            $this->_map[$columnName] = $columnConfig[self::COLUMN_FIELD_ORIGINAL_NAME];
            $this->_columnsConfig[$columnName] = $columnConfig;
        }
    }

    /**
     * @inheritDoc
     */
    public function getComponentName(): string
    {
        return static::NAME;
    }

    /**
     * @inheritDoc
     */
    public function prepare()
    {
        $this->uiComponentGenerator->generateChildComponentsFromArray(
            $this,
            $this->getChildComponentsAsArray()
        );

        parent::prepare();
    }

    /**
     * Get child components as array
     *
     * @return array
     * @throws LocalizedException
     */
    public function getChildComponentsAsArray(): array
    {
        return [
            'modal' => $this->getGenericModal(),
            'button_set' => $this->getButtonSet(),
            $this->getName() => $this->getGrid(),
            'listing-renderer' => $this->getListingRenderer()
        ];
    }

    /**
     * Get grid component
     *
     * @return array
     * @throws LocalizedException
     */
    public function getGrid(): array
    {
        $dndEnabled =
            $this->getConfigByPath('grid/dndEnabled') ?? true;

        return [
            'data' => [
                'componentClassName' => DynamicRows::class,
                'config' =>[
                    'additionalClasses' => 'admin__field-wide',
                    'componentType' => DynamicRows::NAME,
                    'label' => null,
                    'columnsHeader' => false,
                    'columnsHeaderAfterRender' => true,
                    'renderDefaultRecord' => false,
                    'template' => 'ui/dynamic-rows/templates/grid',
                    'component' => 'Magento_Ui/js/dynamic-rows/dynamic-rows-grid',
                    'addButton' => false,
                    'recordTemplate' => 'record',
                    'dataScope' => '',
                    'deleteButtonLabel' => __('Remove'),
                    'dataProvider' => $this->generateUniqNamespace(),
                    'map' => $this->_map,
                    'dndConfig' => [
                        'enabled' => $dndEnabled
                    ],
                    'links' => [
                        'insertData' =>
                            '${ $.provider }:${ $.dataProvider }',
                        '__disableTmpl' => ['insertData' => false]
                    ],
                    'sortOrder' => 2,
                ]
            ],
            'context' => $this->context,
            'children' => [
                'record' => [
                    'data' => [
                        'config' => [
                            'componentType' => 'container',
                            'isTemplate' => true,
                            'is_collection' => true,
                            'component' => 'Magento_Ui/js/dynamic-rows/record',
                            'dataScope' => ''
                        ]
                    ],
                    'context' => $this->context,
                    'children' => $this->fillMeta()
                ]
            ]
        ];
    }

    /**
     * Fill meta
     *
     * @return array[]
     */
    public function fillMeta(): array
    {
        $columns = [];

        foreach ($this->_columnsConfig as $columnName => $columnConfig) {
            $method = 'get' . ucfirst($columnConfig[self::COLUMN_FIELD_TYPE]) . 'Column';
            $columns[] = $this->{$method}($columnName, $columnConfig);
        }

        $columns[] = $this->getActionDeleteColumn();
        $columns[] = $this->getPositionColumn();

        return array_merge(...$columns);
    }

    /**
     * Get text column component
     *
     * @param string $columnName
     * @param array $columnConfig
     * @return array
     */
    public function getTextColumn(
        string $columnName,
        array $columnConfig
    ): array {
        return [
            $columnName => [
                'data' => [
                    'config' => [
                        'componentType' => Field::NAME,
                        'formElement' => Input::NAME,
                        'elementTmpl' => 'ui/dynamic-rows/cells/text',
                        'component' => 'Magento_Ui/js/form/element/text',
                        'template' => 'ui/form/field',
                        'dataType' => Text::NAME,
                        'dataScope' => $columnName,
                        'fit' => $columnConfig[self::COLUMN_FIELD_FIT],
                        'label' => $columnConfig[self::COLUMN_FIELD_LABEL],
                        'sortOrder' => $columnConfig[self::COLUMN_FIELD_SORT_ORDER],
                    ]
                ],
                'context' => $this->context
            ]
        ];
    }

    /**
     * Get thumbnail column component
     *
     * @param string $columnName
     * @param array $columnConfig
     * @return array
     */
    public function getThumbnailColumn(
        string $columnName,
        array $columnConfig
    ): array {
        return [
            $columnName => [
                'data' => [
                    'config' => [
                        'componentType' => Field::NAME,
                        'formElement' => Input::NAME,
                        'elementTmpl' => 'ui/dynamic-rows/cells/thumbnail',
                        'component' => 'Magento_Ui/js/form/element/abstract',
                        'template' => 'ui/form/field',
                        'dataType' => Text::NAME,
                        'dataScope' => $columnName,
                        'fit' => $columnConfig[self::COLUMN_FIELD_FIT],
                        'label' => $columnConfig[self::COLUMN_FIELD_LABEL],
                        'sortOrder' => $columnConfig[self::COLUMN_FIELD_SORT_ORDER],
                    ]
                ],
                'context' => $this->context
            ]
        ];
    }

    /**
     * Get action delete column component
     *
     * @return array
     */
    public function getActionDeleteColumn(): array
    {
        return [
            'actionDelete' => [
                'data' => [
                    'config' => [
                        'template' => 'ui/dynamic-rows/cells/action-delete',
                        'elementTmpl' => 'ui/form/element/input',
                        'component' => 'Magento_Ui/js/dynamic-rows/action-delete',
                        'additionalClasses' => 'data-grid-actions-cell',
                        'componentType' => 'actionDelete',
                        'dataType' => Text::NAME,
                        'label' => __('Actions'),
                        'sortOrder' => 70,
                        'fit' => true,
                    ]
                ],
                'context' => $this->context
            ]
        ];
    }

    /**
     * Get position column component
     *
     * @return array
     */
    public function getPositionColumn(): array
    {
        return [
            'position' => [
                'data' => [
                    'config' => [
                        'component' => 'Magento_Ui/js/form/element/abstract',
                        'template' => 'ui/form/field',
                        'elementTmpl' => 'ui/form/element/input',
                        'dataType' => Number::NAME,
                        'formElement' => Input::NAME,
                        'componentType' => Field::NAME,
                        'dataScope' => 'position',
                        'visible' => false,
                    ]
                ],
                'context' => $this->context
            ]
        ];
    }

    /**
     * Get button set component
     *
     * @return array
     * @throws LocalizedException
     */
    public function getButtonSet(): array
    {
        $content =
            $this->getConfigByPath('button_set/main_title') ?? __('Select Items');
        $buttonTitle =
            $this->getConfigByPath('button_set/button_add/title') ?? __('Add Items');
        $additionalButtonAddActions =
            $this->getConfigByPath('button_set/button_add/additionalActions') ?? [];

        return [
            'data' => [
                'config' => [
                    'formElement' => 'container',
                    'componentType' => 'container',
                    'label' => $this->getRequiredConfigValueByPath('label'),
                    'content' => $content,
                    'template' => 'Grasch_AdminUi/entities-selector/complex',
                    'component' => 'uiComponent'
                ]
            ],
            'context' => $this->context,
            'children' => [
                'button_add' => [
                    'data' => [
                        'config' => [
                            'formElement' => 'container',
                            'componentType' => 'container',
                            'component' => 'Magento_Ui/js/form/components/button',
                            'title' => $buttonTitle,
                            'provider' => null,
                            'actions' => array_merge(
                                [
                                    [
                                        'targetName' => '${ $.parentName.replace(".button_set", "") }.modal',
                                        'actionName' => 'toggleModal',
                                    ],
                                    [
                                        'targetName' =>
                                            '${ $.parentName.replace(".button_set", "") }.listing-renderer',
                                        'actionName' => 'render',
                                    ],
                                ],
                                $additionalButtonAddActions
                            )
                        ]
                    ],
                    'context' => $this->context
                ]
            ]
        ];
    }

    /**
     * Get selections provider
     *
     * @return string
     * @throws LocalizedException
     */
    protected function getSelectionsProvider(): string
    {
        $namespace = $this->getRequiredConfigValueByPath('namespace');

        return sprintf(
            '%s.%s.%s.%s',
            $namespace,
            $namespace,
            $this->getRequiredConfigValueByPath('columnsName'),
            $this->getRequiredConfigValueByPath('selectionsColumnName')
        );
    }

    /**
     * Get columns provider
     *
     * @return string
     * @throws LocalizedException
     */
    protected function getColumnsProvider(): string
    {
        $namespace = $this->getRequiredConfigValueByPath('namespace');

        return sprintf(
            '%s.%s.%s',
            $namespace,
            $namespace,
            $this->getRequiredConfigValueByPath('columnsName')
        );
    }

    /**
     * Get external provider
     *
     * @return string
     * @throws LocalizedException
     */
    protected function getExternalProvider(): string
    {
        $namespace = $this->getRequiredConfigValueByPath('namespace');

        return sprintf('%s.%s_data_source', $namespace, $namespace);
    }

    /**
     * Get listing renderer component
     *
     * @return array
     * @throws LocalizedException
     */
    public function getListingRenderer(): array
    {
        return [
            'data' => [
                'config' => [
                    'component' => 'Grasch_AdminUi/js/view/components/entities-selector/insert-listing/renderer',
                    'selectionsProvider' => $this->getSelectionsProvider(),
                    'columnsProvider' => $this->getColumnsProvider(),
                    'insertListing' => 'uniq_ns = ' . $this->generateUniqNamespace(),
                    'grid' => '${ $.parentName }.' . $this->getName()
                ]
            ],
            'context' => $this->context
        ];
    }

    /**
     * Get generic modal component
     *
     * @return array
     * @throws LocalizedException
     */
    public function getGenericModal(): array
    {
        $namespace = $this->getRequiredConfigValueByPath('namespace');
        $externalProvider = $this->getExternalProvider();
        $selectionsProvider = $this->getSelectionsProvider();

        return [
            'data' => [
                'componentClassName' => Modal::class,
                'config' => [
                    'component' => 'Magento_Ui/js/modal/modal-component',
                    'provider' => null,
                    'options' => [
                        'buttons' => $this->getGenericModalButtons(),
                        'type' => 'slide',
                    ],
                ],
            ],
            'context' => $this->context,
            'children' => [
                $namespace => [
                    'data' => [
                        'config' => [
                            'autoRender' => false,
                            'componentType' => 'insertListing',
                            'component' => 'Magento_Ui/js/form/components/insert-listing',
                            'firstLoad' => true,
                            'dataScope' => $namespace,
                            'externalProvider' => $externalProvider,
                            'selectionsProvider' => $selectionsProvider,
                            'ns' => $namespace,
                            'uniq_ns' => $this->generateUniqNamespace(),
                            'render_url' => $this->urlBuilder->getUrl('mui/index/render'),
                            'realTimeLink' => true,
                            'dataLinks' => [
                                'imports' => false,
                                'exports' => true
                            ],
                            'params' => [
                                'namespace' => '${ $.ns }',
                                'js_modifier' => [
                                    'params' => $this->getJsModifierParams()
                                ]
                            ],
                            'links' => [
                                'value' => '${ $.provider }:${ $.uniq_ns }'
                            ],
                            'behaviourType' => 'simple',
                            'externalFilterMode' => true,
                        ]
                    ],
                    'context' => $this->context
                ]
            ]
        ];
    }

    /**
     * Get js modifier params
     *
     * @return array
     * @throws LocalizedException
     */
    public function getJsModifierParams(): array
    {
        $params = ['columns_name' => $this->getRequiredConfigValueByPath('columnsName')];

        $limit = $this->getConfigByPath('limit/max');
        if ($limit) {
            $params['limiter'] = [
                'selectionsProvider' => $this->getSelectionsProvider(),
                'limit' => $limit
            ];
        }

        return $params;
    }

    /**
     * Get generic modal buttons component
     *
     * @return array
     * @throws LocalizedException
     */
    public function getGenericModalButtons(): array
    {
        $cancelButtonText =
            $this->getConfigByPath('modal/options/buttons/cancel/title') ?? __('Cancel');
        $additionalCancelActions =
            $this->getConfigByPath('modal/options/buttons/cancel/additionalActions') ?? [];

        $saveButtonText =
            $this->getConfigByPath('modal/options/buttons/save/title') ?? __('Add Selected Items');
        $additionalSaveActions =
            $this->getConfigByPath('modal/options/buttons/save/additionalActions') ?? [];

        return [
            [
                'text' => $cancelButtonText,
                'actions' => array_merge(
                    [
                        'closeModal'
                    ],
                    $additionalCancelActions
                )
            ],
            [
                'text' => $saveButtonText,
                'class' => 'action-primary',
                'actions' => array_merge(
                    [
                        [
                            'targetName' => 'uniq_ns = ' . $this->generateUniqNamespace(),
                            'actionName' => 'save'
                        ],
                        'closeModal'
                    ],
                    $additionalSaveActions
                )
            ],
        ];
    }

    /**
     * Get require config value by path
     *
     * @param string $path
     * @return array|mixed
     * @throws LocalizedException
     */
    public function getRequiredConfigValueByPath(string $path)
    {
        $value = $this->getConfigByPath($path);
        if ($value === null) {
            throw new LocalizedException(__(
                'The "%1" configuration parameter is required for the "%2" field.',
                'config/' . $path,
                $this->getName()
            ));
        }

        return $value;
    }

    /**
     * Get config by path
     *
     * @param string $path
     * @return array|mixed|null
     */
    public function getConfigByPath(string $path)
    {
        return $this->getDataByPath('config/' . $path);
    }

    /**
     * Generate uniq namespace
     *
     * @return string
     * @throws LocalizedException
     */
    protected function generateUniqNamespace(): string
    {
        return sprintf(
            '%s_%s',
            $this->getRequiredConfigValueByPath('namespace'),
            $this->getName()
        );
    }
}
