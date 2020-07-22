<?php

namespace App\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Swagger\Annotations as SWG;

/**
 * Class ProductNormalizer
 * @package App\Serializer\Normalizer
 * @SWG\Schema(
 *             type="array",
 *             @SWG\Items(
 *                  @SWG\Property(type="integer", property="id"),
 *                  @SWG\Property(type="String", property="md5"),
 *                  @SWG\Property(type="string", property="name"),
 *                  @SWG\Property(type="string", property="description"),
 *                  @SWG\Property(type="string", property="url"),
 *                  @SWG\Property(type="boolean", property="active"),
 *                  @SWG\Property(type="array", property="brand", description="The brand of product",
 *                      @SWG\Items(
 *                          @SWG\Property(type="integer", property="id"),
 *                          @SWG\Property(type="string", property="name"),
 *                      )
 *                  ),
 *                  @SWG\Property(type="array", property="categories", description="All categories of product",
 *                      @SWG\Items(
 *                          @SWG\Property(type="array", property="category",
 *                              @SWG\Items(
 *                                  @SWG\Property(type="integer", property="id"),
 *                                  @SWG\Property(type="string", property="name"),
 *                              ),
 *                          ),
 *
 *                      )
 *                  ),
 *              )
 *     )
 */

class ProductNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    private $normalizer;

    public function __construct(ObjectNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    public function normalize($object, $format = null, array $context = array()): array
    {
        $data = $this->normalizer->normalize($object, $format, $context);

        // Here: add, edit, or delete some data

        return $data;
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof \App\Entity\BlogPost;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
