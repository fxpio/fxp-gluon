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

use Fxp\Component\Block\AbstractTypeExtension;
use Fxp\Component\Block\BlockInterface;
use Fxp\Component\Block\BlockView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Table Column Pager Block Extension.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
abstract class AbstractTableColumnPagerExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        $attr = $view->vars['label_attr'];

        if ($options['sortable']) {
            $attr['data-table-pager-sortable'] = 'true';
        }

        $view->vars = array_replace($view->vars, [
            'sortable' => $options['sortable'],
            'label_attr' => $attr,
        ]);

        if ($options['sortable'] && !isset($attr['data-table-sort'])) {
            $view->vars['value'] = \is_string($view->vars['value']) ? $view->vars['value'] : '';
            $view->vars['value'] .= '<i class="table-sort-icon fa"></i>';
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'sortable' => false,
        ]);

        $resolver->addAllowedTypes('sortable', 'bool');
    }
}
