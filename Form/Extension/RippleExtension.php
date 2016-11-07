<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Component\Gluon\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Ripple Form Extension.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class RippleExtension extends AbstractTypeExtension
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
    public function buildView(FormView $view, FormInterface $block, array $options)
    {
        $view->vars = array_replace($view->vars, array(
            'ripple' => $options['ripple'],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'ripple' => false,
        ));

        $resolver->setAllowedTypes('ripple', 'bool');
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return $this->extendedType;
    }
}
