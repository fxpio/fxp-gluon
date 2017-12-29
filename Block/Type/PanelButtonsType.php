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
use Fxp\Component\Block\BlockInterface;
use Fxp\Component\Block\BlockView;
use Fxp\Component\Block\Exception\InvalidConfigurationException;
use Fxp\Component\Block\Extension\Core\Type\BlockType;
use Fxp\Component\Block\Extension\Core\Type\FormType;
use Fxp\Component\Block\Util\BlockUtil;
use Fxp\Component\Bootstrap\Block\Type\ButtonType;
use Fxp\Component\FormExtensions\Form\Util\FormUtil;
use Symfony\Component\Form\Extension\Core\Type\ButtonType as FormButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Panel Buttons Block Type.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class PanelButtonsType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildBlock(BlockBuilderInterface $builder, array $options)
    {
        if ($options['scrollable']) {
            $builder->add('_nav_scrollable', NavScrollableType::class);
            $builder->get('_nav_scrollable')->add('_navButtonGroup', BlockType::class, array(
                'attr' => array(
                    'class' => $options['class_nav'],
                ),
            ));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addChild(BlockInterface $child, BlockInterface $block, array $options)
    {
        // scrollable
        if (BlockUtil::isBlockType($child, NavScrollableType::class)) {
            if ($block->getAttribute('already_nav_scrollable')) {
                $navScrollable = $block->get($block->getAttribute('already_nav_scrollable'));

                foreach ($navScrollable->all() as $action) {
                    $child->add($action);
                }

                $block->remove($block->getAttribute('already_nav_scrollable'));
            } else {
                $block->setAttribute('already_nav_scrollable', $child->getName());
            }

            $attr = $child->getOption('attr');
            $attr['data-class-nav'] = $options['class_nav'];
            $attr['data-content-selector'] = '.nav-btn-group';
            $child->setOption('attr', $attr);

            // button
        } elseif (BlockUtil::isBlockType($child, ButtonType::class)
                || (BlockUtil::isBlockType($child, FormType::class)
                    && FormUtil::isFormType($child->getForm(), array(\Symfony\Component\Form\Extension\Core\Type\FormType::class, FormButtonType::class, SubmitType::class)))) {
            $parent = $this->findParentButtons($block);

            if ($parent !== $block) {
                $parent->add($child);
            }

            if (null === $child->getOption('size')) {
                if (null !== $child->getForm() && FormUtil::isFormType($child->getForm(), \Symfony\Component\Form\Extension\Core\Type\FormType::class)) {
                    /* @var array $fOtps */
                    $fOtps = $child->getOption('options', array());
                    $fOtps['size'] = $options['button_size'];
                    $child->setOption('options', $fOtps);
                } else {
                    $child->setOption('size', $options['button_size']);
                }
            }

            // other
        } else {
            $msg = 'The "panel_buttons" child block (name: "%s") must be a "button" or "form" block type';
            throw new InvalidConfigurationException(sprintf($msg, $child->getName()));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        $view->vars = array_replace($view->vars, array(
            'scrollable' => $options['scrollable'],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(BlockView $view, BlockInterface $block, array $options)
    {
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
            'scrollable' => true,
            'class_nav' => 'nav-btn-group',
            'button_size' => null,
        ));

        $resolver->setAllowedTypes('scrollable', 'bool');
        $resolver->setAllowedTypes('class_nav', 'string');
        $resolver->setAllowedTypes('button_size', array('null', 'string'));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'panel_buttons';
    }

    /**
     * Finds the parent block of buttons.
     *
     * @param BlockInterface $block
     *
     * @return BlockInterface
     */
    protected function findParentButtons(BlockInterface $block)
    {
        if ($block->getAttribute('block_parent_buttons')) {
            return $block->getAttribute('block_parent_buttons');
        }

        $parent = $block;

        if ($block->getOption('scrollable')) {
            /* @var BlockInterface $block */
            foreach ($block->all() as $child) {
                if (BlockUtil::isBlockType($child, NavScrollableType::class)) {
                    /* @var BlockInterface $child */
                    foreach ($child->all() as $subChild) {
                        if (BlockUtil::isBlockType($subChild, BlockType::class)) {
                            $parent = $subChild;
                            break 2;
                        }
                    }
                }
            }
        }

        $block->setAttribute('block_parent_buttons', $parent);

        return $parent;
    }
}
