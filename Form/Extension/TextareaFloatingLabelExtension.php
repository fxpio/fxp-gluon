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

use Symfony\Component\Form\Extension\Core\Type\TextareaType;

/**
 * Textarea Floating Label Form Extension.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class TextareaFloatingLabelExtension extends AbstractFloatingLabelExtension
{
    /**
     * {@inheritdoc}
     */
    public static function getExtendedTypes()
    {
        return [TextareaType::class];
    }
}
