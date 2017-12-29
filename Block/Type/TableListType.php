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
use Fxp\Component\Block\BlockBuilderInterface;
use Fxp\Component\Block\BlockFactoryInterface;
use Fxp\Component\Block\BlockInterface;
use Fxp\Component\Block\BlockView;
use Fxp\Component\Block\Exception\InvalidConfigurationException;
use Fxp\Component\Block\Util\BlockUtil;
use Fxp\Component\Bootstrap\Block\Type\DropdownItemType;
use Fxp\Component\Bootstrap\Block\Type\DropdownType;
use Fxp\Component\Bootstrap\Block\Type\TableHeaderType;
use Fxp\Component\Bootstrap\Block\Type\TableType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class TableListType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildBlock(BlockBuilderInterface $builder, array $options)
    {
        $builder->setAttribute('block_factory', $builder->getBlockFactory());
    }

    /**
     * {@inheritdoc}
     */
    public function addChild(BlockInterface $child, BlockInterface $block, array $options)
    {
        if (BlockUtil::isBlockType($child, [TableColumnListSortableType::class])) {
            $block->getData()->addColumn($child);
        } elseif (!BlockUtil::isBlockType($child, [TableHeaderType::class, TableColumnSelectType::class, TablePagerType::class, TableColumnListAdapterType::class])) {
            $msg = 'The "%s" child block (name: "%s") must be a "%s" or "%s" block type ("%s" type given)';
            throw new InvalidConfigurationException(sprintf($msg, get_class($block->getConfig()->getType()->getInnerType()),
                $child->getName(), TableColumnListAdapterType::class, TableColumnListSortableType::class,
                get_class($child->getConfig()->getType()->getInnerType())));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeChild(BlockInterface $child, BlockInterface $block, array $options)
    {
        if (!BlockUtil::isBlockType($child, TableColumnListSortableType::class)) {
            $block->getData()->removeColumn($child->getName());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        BlockUtil::addAttributeClass($view, 'table-list');

        if ($options['multi_selectable']) {
            BlockUtil::addAttributeClass($view, 'table-list-multiple');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(BlockView $view, BlockInterface $block, array $options)
    {
        if (!isset($view->vars['pager'])) {
            return;
        }

        /* @var BlockView[] $sortColumns */
        $sortColumns = [];

        foreach ($view->children as $name => $child) {
            if (in_array('table_column_list_sort', $child->vars['block_prefixes'])) {
                $sortColumns[] = $child;
                unset($view->children[$name]);
            }
        }

        if (count($sortColumns) > 0) {
            /* @var BlockFactoryInterface $factory */
            $factory = $block->getConfig()->getAttribute('block_factory');
            $sortDropdown = $factory->create(DropdownType::class, null, ['ripple' => true, 'wrapper' => false, 'attr' => ['class' => 'table-pager-list-sort-menu']]);

            foreach ($sortColumns as $sortColumn) {
                $colOptions = [
                    'label' => $sortColumn->vars['label'],
                    'translation_domain' => $sortColumn->vars['translation_domain'],
                    'link_attr' => array_replace($sortColumn->vars['label_attr'], [
                        'data-col-name' => $sortColumn->vars['name'],
                    ]),
                ];
                $sortDropdown->add($sortColumn->vars['name'], DropdownItemType::class, $colOptions);
            }

            $view->vars['pager']->vars['sort_columns'] = $sortDropdown;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        if (in_array('footable', $resolver->getDefinedOptions())) {
            $resolver->setNormalizer('footable', function () {
                return [
                    'enabled' => false,
                ];
            });
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return TableType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'table_list';
    }
}
