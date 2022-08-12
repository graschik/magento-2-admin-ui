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

namespace Grasch\AdminUi\Model;

use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentInterface;
use Magento\Ui\Component\Container;

class UiComponentGenerator
{
    /**
     * @var ObjectManagerInterface
     */
    private ObjectManagerInterface $objectManager;

    /**
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(
        ObjectManagerInterface $objectManager
    ) {
        $this->objectManager = $objectManager;
    }

    /**
     * @param UiComponentInterface $parent
     * @param array $children
     * @return UiComponentInterface
     */
    public function generateChildComponentsFromArray(
        UiComponentInterface $parent,
        array $children
    ): UiComponentInterface {
        foreach ($children as $childName => $child) {
            $component = $this->createUiComponent(
                $childName,
                $child['data'],
                $child['context']
            );
            if (isset($child['children'])) {
                $this->generateChildComponentsFromArray(
                    $component,
                    $child['children']
                );
            }
            $parent->addComponent($childName, $component);
        }

        return $parent;
    }

    /**
     * @param string $name
     * @param array $data
     * @param ContextInterface $context
     * @return UiComponentInterface
     */
    public function createUiComponent(
        string $name,
        array $data,
        ContextInterface $context
    ): UiComponentInterface {
        $data['name'] = $name;

        return $this->objectManager->create(
            $data['componentClassName'] ?? Container::class,
            [
                'data' => $data,
                'context' => $context
            ]
        );
    }
}
