<?php

/*
 * This file is part of the Fxp package.
 *
 * (c) François Pluchino <francois.pluchino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fxp\Component\Gluon\Block\Extension;

use Fxp\Component\Block\AbstractTypeExtension;
use Fxp\Component\Block\BlockInterface;
use Fxp\Component\Block\BlockView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Panel Content Block Extension.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
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
        $view->vars = array_replace($view->vars, [
            'style' => $options['style'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'style' => null,
        ]);

        $resolver->addAllowedTypes('style', ['null', 'string']);

        $resolver->addAllowedValues('style', [
            null,
            'primary-box',
            'accent-box',
            'success-box',
            'info-box',
            'warning-box',
            'danger-box',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return $this->extendedType;
    }
}
