<?php

/*
 * This file is part of the Fxp package.
 *
 * (c) François Pluchino <francois.pluchino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fxp\Component\Gluon\Block\Type;

use Fxp\Component\Block\AbstractType;
use Fxp\Component\Bootstrap\Block\Type\TableColumnType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Table Column Row Number Block Type.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class TableColumnRowNumberType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'max_width' => 1,
            'min_width' => 1,
            'footable' => [
                'ignore' => true,
            ],
            'label_attr' => [
                'class' => 'table-row-number',
            ],
            'attr' => [
                'class' => 'table-row-number',
            ],
        ]);
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
