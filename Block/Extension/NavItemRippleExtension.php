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

use Fxp\Component\Bootstrap\Block\Type\NavItemType;

/**
 * Nav Item Ripple Block Extension.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class NavItemRippleExtension extends AbstractRippleExtension
{
    /**
     * {@inheritdoc}
     */
    public static function getExtendedTypes()
    {
        return [NavItemType::class];
    }
}
