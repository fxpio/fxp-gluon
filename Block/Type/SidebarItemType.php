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
use Fxp\Component\Block\Util\BlockUtil;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Sidebar Item Block Type.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class SidebarItemType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function addParent(BlockInterface $parent, BlockInterface $block, array $options)
    {
        $isContext = $block->getOption('context_menu');

        if (BlockUtil::isBlockType($parent, SidebarType::class)) {
            $sidebar = $parent;
        } elseif (BlockUtil::isBlockType($parent, SidebarGroupType::class) && null !== $parent->getParent()
                && BlockUtil::isBlockType($parent->getParent(), SidebarType::class)) {
            $sidebar = $parent->getParent();
            $isContext = $isContext || $parent->getOption('context_menu');
        } else {
            return;
        }

        if (null !== ($selection = $sidebar->getOption('selection'))) {
            $block->setOption('active', $block->getOption('data_item') === $selection);
        }

        if ($isContext && null !== ($contextSelection = $sidebar->getOption('context_selection'))) {
            $block->setOption('active', $block->getOption('data_item') === $contextSelection);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        $linkAttr = $options['link_attr'];

        if (null !== $options['src'] && !$options['disabled']) {
            $linkAttr['href'] = $options['src'];
        }

        if ($options['disabled'] && isset($linkAttr['tabindex'])) {
            unset($linkAttr['tabindex']);
        }

        $view->vars = array_replace($view->vars, array(
            'link_attr' => $linkAttr,
            'active' => $options['active'],
            'disabled' => $options['disabled'],
            'mini' => $options['mini'],
            'context_menu' => $options['context_menu'],
            'data_item' => $options['data_item'],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'src' => '#',
            'link_attr' => array(),
            'active' => false,
            'disabled' => false,
            'chained_block' => true,
            'mini' => false,
            'context_menu' => false,
            'data_item' => null,
        ));

        $resolver->setAllowedTypes('src', array('null', 'string'));
        $resolver->setAllowedTypes('link_attr', 'array');
        $resolver->setAllowedTypes('active', 'bool');
        $resolver->setAllowedTypes('disabled', 'bool');
        $resolver->setAllowedTypes('mini', 'bool');
        $resolver->setAllowedTypes('context_menu', 'bool');
        $resolver->setAllowedTypes('data_item', array('null', 'string'));

        $resolver->setNormalizer('src', function (Options $options, $value = null) {
            if (isset($options['data'])) {
                return $options['data'];
            }

            return $value;
        });
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'sidebar_item';
    }
}
