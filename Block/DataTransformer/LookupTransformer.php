<?php

/*
 * This file is part of the Fxp package.
 *
 * (c) François Pluchino <francois.pluchino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fxp\Component\Gluon\Block\DataTransformer;

use Fxp\Component\Block\DataTransformerInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\PropertyAccess\PropertyPath;
use Symfony\Component\PropertyAccess\PropertyPathInterface;

/**
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class LookupTransformer implements DataTransformerInterface
{
    /**
     * @var PropertyPathInterface
     */
    private $propertyPath;

    /**
     * @var PropertyAccessorInterface
     */
    private $propertyAccessor;

    /**
     * Constructor.
     *
     * @param PropertyPathInterface|string|null $propertyPath     The property path
     * @param PropertyAccessorInterface         $propertyAccessor The property accessor
     */
    public function __construct($propertyPath = null, PropertyAccessorInterface $propertyAccessor = null)
    {
        if (null !== $propertyPath && !$propertyPath instanceof PropertyPathInterface) {
            $propertyPath = new PropertyPath($propertyPath);
        }

        $this->propertyPath = $propertyPath;
        $this->propertyAccessor = $propertyAccessor ?: PropertyAccess::createPropertyAccessor();
    }

    /**
     * {@inheritdoc}
     */
    public function transform($data)
    {
        if ($this->propertyPath instanceof PropertyPathInterface && (\is_object($data) || $data instanceof \ArrayAccess)) {
            $data = $this->propertyAccessor->getValue($data, $this->propertyPath);
        }

        return $data;
    }
}
