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
use Fxp\Component\Bootstrap\Block\Type\ButtonType;
use Fxp\Component\Bootstrap\Block\Type\PanelHeaderType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Panel Actions Block Type.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class PanelActionsType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function addChild(BlockInterface $child, BlockInterface $block, array $options)
    {
        if (BlockUtil::isBlockType($child, ButtonType::class)) {
            $child->setOption('size', 'xs');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addParent(BlockInterface $parent, BlockInterface $block, array $options)
    {
        if (!BlockUtil::isBlockType($parent, array(PanelHeaderType::class, PanelSectionType::class))) {
            $msg = 'The "panel_actions" parent block (name: "%s") must be a "panel_header" or "panel_section" block type';
            throw new InvalidConfigurationException(sprintf($msg, $block->getName()));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(BlockView $view, BlockInterface $block, array $options)
    {
        foreach ($view->children as $name => $child) {
            if (in_array('button', $child->vars['block_prefixes'])) {
                $class = isset($child->vars['attr']['class']) ? $child->vars['attr']['class'] : '';

                if (false !== strpos($class, 'btn-panel-collapse')) {
                    $view->vars['panel_button_collapse'] = $child;

                    unset($view->children[$name]);
                    break;
                }
            }
        }

        if (!is_scalar($view->vars['value'])) {
            $view->vars['value'] = '';
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'inherit_data' => true,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'panel_actions';
    }
}
