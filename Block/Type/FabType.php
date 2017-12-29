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
use Fxp\Component\Bootstrap\Block\Type\ButtonType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class FabType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        $view->vars = array_replace($view->vars, [
            'absolute_position' => str_replace('_', '-', $options['absolute_position']),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(BlockView $view, BlockInterface $block, array $options)
    {
        if (isset($view->vars['dropdown']) && in_array($options['absolute_position'], ['top_right', 'bottom_right'])) {
            /* @var BlockView $dropView */
            $dropView = $view->vars['dropdown'];
            BlockUtil::addAttributeClass($dropView, 'fab-pull-right');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'absolute_position' => null,
            'dropup' => function (Options $options) {
                return in_array($options['absolute_position'], ['bottom_left', 'bottom_right'])
                    ? true
                    : false;
            },
        ]);

        $resolver->addAllowedTypes('absolute_position', ['null', 'string']);

        $resolver->addAllowedValues('absolute_position', [
            null, 'top_left', 'top_right', 'bottom_left', 'bottom_right',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return ButtonType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'fab';
    }
}
