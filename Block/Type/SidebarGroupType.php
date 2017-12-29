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
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Sidebar Group Block Type.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class SidebarGroupType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        $view->vars = array_replace($view->vars, [
            'group_attr' => $options['group_attr'],
            'context_menu' => $options['context_menu'],
        ]);

        if ($options['no_bar']) {
            BlockUtil::addAttributeClass($view, 'no-bar', false, 'group_attr');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'group_attr' => [],
            'context_menu' => false,
            'no_bar' => false,
        ]);

        $resolver->setAllowedTypes('group_attr', 'array');
        $resolver->setAllowedTypes('context_menu', 'bool');
        $resolver->setAllowedTypes('no_bar', 'bool');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'sidebar_group';
    }
}
