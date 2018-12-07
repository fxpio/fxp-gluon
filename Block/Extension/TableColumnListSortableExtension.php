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

use Fxp\Component\Gluon\Block\Type\TableColumnListSortableType;

/**
 * Table Column List Sortable Block Extension.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class TableColumnListSortableExtension extends AbstractTableColumnExtension
{
    /**
     * {@inheritdoc}
     */
    public static function getExtendedTypes()
    {
        return [TableColumnListSortableType::class];
    }
}
