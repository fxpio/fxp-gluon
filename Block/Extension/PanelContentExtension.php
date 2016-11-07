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
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Panel Content Block Extension.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class PanelContentExtension extends AbstractTypeExtension
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
    public function __construct($extendedType)
    {
        $this->extendedType = $extendedType;
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        $view->vars = array_replace($view->vars, array(
            'style' => $options['style'],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'style' => null,
        ));

        $resolver->addAllowedTypes('style', array('null', 'string'));

        $resolver->addAllowedValues('style', array(
            null,
            'primary-box',
            'accent-box',
            'success-box',
            'info-box',
            'warning-box',
            'danger-box',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return $this->extendedType;
    }
}
