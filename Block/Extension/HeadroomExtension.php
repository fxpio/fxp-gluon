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
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Headroom Block Extension.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class HeadroomExtension extends AbstractTypeExtension
{
    /**
     * @var string
     */
    protected $extendedType;

    /**
     * @var string
     */
    protected $dataAttr;

    /**
     * Constructor.
     *
     * @param string $extendedType The extended block type
     * @param string $dataAttr     The name of data attributes in block view
     */
    public function __construct($extendedType, $dataAttr = 'attr')
    {
        $this->extendedType = $extendedType;
        $this->dataAttr = $dataAttr;
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        $hr = $options['headroom'];

        if (!$hr['enabled']) {
            return;
        }

        BlockUtil::addAttribute($view, 'data-headroom', 'true', $this->dataAttr);

        if (null !== $hr['offset']) {
            BlockUtil::addAttribute($view, 'data-offset', $hr['offset'], $this->dataAttr);
        }

        if (null !== $hr['tolerance']) {
            BlockUtil::addAttribute($view, 'data-tolerance', $hr['tolerance'], $this->dataAttr);
        }

        if (null !== $hr['classes']) {
            BlockUtil::addAttribute($view, 'data-classes', $hr['classes'], $this->dataAttr);
        }

        if (null !== $hr['scroller']) {
            BlockUtil::addAttribute($view, 'data-scroller', $hr['scroller'], $this->dataAttr);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'headroom' => [],
        ]);

        $resolver->setAllowedTypes('headroom', ['bool', 'array']);

        $resolver->setNormalizer('headroom', function (Options $options, $value) {
            $headroomResolver = new OptionsResolver();

            $headroomResolver->setDefaults([
                'enabled' => false,
                'offset' => null,
                'tolerance' => null,
                'classes' => null,
                'scroller' => null,
            ]);

            $headroomResolver->setAllowedTypes('enabled', 'bool');
            $headroomResolver->setAllowedTypes('offset', ['null', 'int']);
            $headroomResolver->setAllowedTypes('tolerance', ['null', 'int', 'array']);
            $headroomResolver->setAllowedTypes('classes', ['null', 'array']);
            $headroomResolver->setAllowedTypes('scroller', ['null', 'string']);

            if (is_bool($value)) {
                $value = ['enabled' => $value];
            } elseif (is_array($value) && !array_key_exists('enabled', $value) && count($value) > 0) {
                $value['enabled'] = true;
            }

            return $headroomResolver->resolve($value);
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
