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
use Fxp\Component\Bootstrap\Block\Type\TableColumnType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Table Column Block Extension.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class TableColumnExtension extends AbstractTypeExtension
{
    /**
     * @var string
     */
    protected $extendedType;

    /**
     * Constructor.
     *
     * @param string $extendedType The extended block type
     */
    public function __construct($extendedType = TableColumnType::class)
    {
        $this->extendedType = $extendedType;
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        $source = $block->getParent()->getData();
        $sort = $source->getSortColumn($block->getName());

        if (null !== $sort) {
            $view->vars['label_attr']['data-table-sort'] = $sort;
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
            'align' => null,
            'min_width' => null,
            'max_width' => null,
            'width' => null,
        ]);

        $resolver->addAllowedTypes('align', ['null', 'string']);
        $resolver->addAllowedTypes('min_width', ['null', 'int']);
        $resolver->addAllowedTypes('max_width', ['null', 'int']);

        $resolver->addAllowedValues('align', [null, 'left', 'center', 'right']);

        $resolver->setNormalizer('label_attr', function (Options $options, $value) {
            $class = isset($value['class']) ? $value['class'] : '';
            $style = isset($value['style']) ? $value['style'] : '';

            if ($options['align']) {
                $class = trim($class.' table-'.$options['align']);
            }

            if (null !== $options['min_width']) {
                $style = trim($style.' min-width:'.$options['min_width'].'px;');
            }

            if (null !== $options['max_width']) {
                $style = trim($style.' max-width:'.$options['max_width'].'px;');
            }

            if (null !== $options['width']) {
                $style = trim($style.' width:'.$options['width'].'px;');
            }

            if ('' !== $class) {
                $value['class'] = $class;
            }

            if ('' !== $style) {
                $value['style'] = $style;
            }

            return $value;
        });
        $resolver->setNormalizer('attr', function (Options $options, $value) {
            $class = isset($value['class']) ? $value['class'] : '';

            if ($options['align']) {
                $class = trim($class.' table-'.$options['align']);
            }

            if ('' !== $class) {
                $value['class'] = $class;
            }

            return $value;
        });
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return $this->extendedType;
    }
}
