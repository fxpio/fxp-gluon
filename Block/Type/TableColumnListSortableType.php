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
use Fxp\Component\Block\BlockInterface;
use Fxp\Component\Block\BlockView;
use Fxp\Component\Block\Exception\InvalidConfigurationException;
use Fxp\Component\Block\Util\BlockUtil;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Table Column List Sortable Block Type.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class TableColumnListSortableType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function addParent(BlockInterface $parent, BlockInterface $block, array $options)
    {
        if (!BlockUtil::isBlockType($parent, TableListType::class)) {
            $msg = 'The "table_column_list_sort" parent block (name: "%s") must be a "table_list" block type';
            throw new InvalidConfigurationException(sprintf($msg, $block->getName()));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        $view->vars = array_replace($view->vars, array(
            'index' => $options['index'],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $index = function (Options $options, $value) {
            if (null === $value) {
                $value = $options['block_name'];
            }

            return $value;
        };

        $resolver->setDefaults(array(
            'index' => $index,
            'enabled' => false,
            'sortable' => true,
        ));

        $resolver->setAllowedValues('enabled', false);

        $resolver->setNormalizer('sortable', function () {
            return true;
        });
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'table_column_list_sort';
    }
}
