<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Component\Gluon\Block\Type;

use Sonatra\Component\Block\AbstractType;
use Sonatra\Component\Block\BlockInterface;
use Sonatra\Component\Block\BlockView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Spinner Block Type.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class SpinnerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        $view->vars = array_replace($view->vars, array(
            'floating' => $options['mini'],
            'mini' => $options['mini'],
            'spinner_c' => $options['size'] + 2,
            'spinner_r' => $options['size'],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'floating' => false,
            'mini' => false,
            'size' => 20,
        ));

        $resolver->setAllowedTypes('floating', 'bool');
        $resolver->setAllowedTypes('mini', 'bool');
        $resolver->setAllowedTypes('size', 'int');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'spinner';
    }
}
