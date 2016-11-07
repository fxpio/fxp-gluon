<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Component\Gluon\Block\Extension;

use Sonatra\Component\Block\AbstractTypeExtension;
use Sonatra\Component\Block\BlockInterface;
use Sonatra\Component\Block\BlockView;
use Sonatra\Component\Bootstrap\Block\Type\ProgressBarType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * ProgressBar Block Extension.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class ProgressBarExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        $view->vars = array_replace($view->vars, array(
            'indeterminate' => $options['indeterminate'],
            'floating_label' => $options['floating_label'],
            'large' => $options['large'],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'indeterminate' => false,
            'floating_label' => false,
            'large' => false,
        ));

        $resolver->addAllowedTypes('indeterminate', 'bool');
        $resolver->addAllowedTypes('floating_label', array('bool', 'string'));
        $resolver->addAllowedTypes('large', 'bool');

        $resolver->addAllowedValues('style', array('accent'));
        $resolver->addAllowedValues('floating_label', array(true, false, 'hover'));
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return ProgressBarType::class;
    }
}
