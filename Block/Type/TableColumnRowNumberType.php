<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Component\Gluon\Block\Type;

use Sonatra\Component\Block\AbstractType;
use Sonatra\Component\Bootstrap\Block\Type\TableColumnType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Table Column Row Number Block Type.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class TableColumnRowNumberType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'max_width' => 1,
            'min_width' => 1,
            'footable' => array(
                'ignore' => true,
            ),
            'label_attr' => array(
                'class' => 'table-row-number',
            ),
            'attr' => array(
                'class' => 'table-row-number',
            ),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return TableColumnType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'table_column_row_number';
    }
}
