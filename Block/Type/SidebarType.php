<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\GluonBundle\Block\Type;

use Sonatra\Bundle\BlockBundle\Block\AbstractType;
use Sonatra\Bundle\BlockBundle\Block\BlockView;
use Sonatra\Bundle\BlockBundle\Block\BlockInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;

/**
 * Sidebar Block Type.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class SidebarType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        $attr = $view->vars['attr'];
        $attr['data-sidebar'] = $this->formatBoolean(true);
        $attr['data-force-toggle'] = is_bool($options['force_toggle']) ? $this->formatBoolean($options['force_toggle']) : $options['force_toggle'];
        $attr['data-locked'] = $this->formatBoolean($options['locked']);

        if (null !== $options['sticky_header']) {
            $attr['data-scroller---sticky-header'] = $this->formatBoolean($options['sticky_header']);
        }

        if (null !== $options['position']) {
            $attr['data-position'] = $options['position'];
        }

        if (null !== $options['scrollbar']) {
            $attr['data-scroller--scrollbar'] = $this->formatBoolean($options['scrollbar']);
        }

        if (null !== $options['disable_keyboard']) {
            $attr['data-disabled-keyboard'] = $options['disable_keyboard'];
        }

        if (null !== $options['open_on_hover']) {
            $attr['data-open-on-hover'] = $this->formatBoolean($options['open_on_hover']);
        }

        if (null !== $options['min_lock_width']) {
            $attr['data-min-lock-width'] = $options['min_lock_width'];
        }

        if (null !== $options['toggle_id']) {
            $attr['data-toggle-id'] = $options['toggle_id'];
        }

        $view->vars = array_replace($view->vars, array(
            'style' => $options['style'],
            'attr' => $attr,
            'sticky_header' => $options['sticky_header'],
            'opened' => $options['opened'],
            'locked' => $options['locked'],
            'position' => 'right' === $options['position'] ? $options['position'] : 'left',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $disableKeyboard = function (Options $options, $value) {
            if ('right' === $options['position']) {
                return true;
            }

            return $value;
        };

        $forceToggle = function (Options $options) {
            return ('right' === $options['position']) ? false : true;
        };

        $resolver->setDefaults(array(
            'open_on_hover' => null,
            'force_toggle' => $forceToggle,
            'min_lock_width' => null,
            'sticky_header' => false,
            'style' => 'default',
            'toggle_id' => null,
            'opened' => false,
            'locked' => false,
            'position' => null,
            'scrollbar' => null,
            'disable_keyboard' => $disableKeyboard,
        ));

        $resolver->setAllowedTypes('open_on_hover', array('null', 'bool'));
        $resolver->setAllowedTypes('force_toggle', array('bool', 'string'));
        $resolver->setAllowedTypes('min_lock_width', array('null', 'int'));
        $resolver->setAllowedTypes('sticky_header', array('null', 'bool'));
        $resolver->setAllowedTypes('style', 'string');
        $resolver->setAllowedTypes('toggle_id', array('null', 'string'));
        $resolver->setAllowedTypes('opened', array('bool', 'string'));
        $resolver->setAllowedTypes('locked', 'bool');
        $resolver->setAllowedTypes('position', array('null', 'string'));
        $resolver->setAllowedTypes('scrollbar', array('null', 'bool'));
        $resolver->setAllowedTypes('disable_keyboard', array('null', 'bool'));

        $resolver->setAllowedValues('force_toggle', array(false, true, 'always'));
        $resolver->setAllowedValues('style', array('default', 'inverse'));
        $resolver->setAllowedValues('opened', array(false, true, 'force'));
        $resolver->setAllowedValues('position', array(null, 'left', 'right'));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sidebar';
    }

    /**
     * Format the boolean to string html tag value.
     *
     * @param bool $value
     *
     * @return string
     */
    protected function formatBoolean($value)
    {
        return $value ? 'true' : 'false';
    }
}
