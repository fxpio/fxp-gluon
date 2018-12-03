<?php

/*
 * This file is part of the Fxp package.
 *
 * (c) François Pluchino <francois.pluchino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fxp\Component\Gluon\Form\Extension;

use Symfony\Component\Form\Extension\Core\Type\NumberType;

/**
 * Number Floating Label Form Extension.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class NumberFloatingLabelExtension extends AbstractFloatingLabelExtension
{
    /**
     * {@inheritdoc}
     */
    public static function getExtendedTypes()
    {
        return [NumberType::class];
    }
}
