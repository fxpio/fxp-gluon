<?php

/*
 * This file is part of the Fxp package.
 *
 * (c) François Pluchino <francois.pluchino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fxp\Component\Gluon\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Textarea Form Extension.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
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
        $resolver->setDefaults([
            'auto_resize' => true,
        ]);

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
