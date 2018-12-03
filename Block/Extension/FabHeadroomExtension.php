<?php

/*
 * This file is part of the Fxp package.
 *
 * (c) François Pluchino <francois.pluchino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fxp\Component\Gluon\Block\Extension;

use Fxp\Component\Gluon\Block\Type\FabType;

/**
 * Fab Headroom Block Extension.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class FabHeadroomExtension extends AbstractHeadroomExtension
{
    /**
     * {@inheritdoc}
     */
    public static function getExtendedTypes()
    {
        return [FabType::class];
    }
}
