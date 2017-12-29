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
use Fxp\Component\Block\Util\BlockUtil;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Pull Block Extension.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class PullExtension extends AbstractTypeExtension
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
        if (is_array($options['pull'])) {
            foreach ($options['pull'] as $pull) {
                BlockUtil::addAttributeClass($view, $options['pull_prefix'].'pull-'.$pull);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'pull' => null,
            'pull_prefix' => null,
        ]);

        $resolver->setAllowedTypes('pull', ['null', 'string', 'array']);
        $resolver->setAllowedTypes('pull_prefix', ['null', 'string']);

        $resolver->setNormalizer('pull', function (Options $options, $value) {
            if (is_string($value)) {
                $value = [$value];
            }

            if (is_array($value)) {
                foreach ($value as $pull) {
                    if (!in_array($pull, ['top', 'right'])) {
                        $msg = 'The option "pull" with value "%s" is invalid';
                        throw new InvalidOptionsException(sprintf($msg, $pull));
                    }
                }
            }

            return $value;
        });
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return $this->extendedType;
    }
}
