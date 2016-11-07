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
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Textarea Form Extension.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class TextareaExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if ($options['auto_resize']) {
            $view->vars['attr']['data-resizing-textarea'] = 'true';
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'auto_resize' => true,
        ));

        $resolver->setAllowedTypes('auto_resize', 'bool');
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return TextareaType::class;
    }
}
