<?php
// api/src/Encoder/MultipartDecoder.php

namespace App\Encoder;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Encoder\DecoderInterface;

final class MultipartDecoder implements DecoderInterface
{
    public const FORMAT = 'multipart';

    public function __construct(private RequestStack $requestStack) {}

    /**
     * {@inheritdoc}
     */
    public function decode($data, $format,$context = []): ?array
    {
        $request = $this->requestStack->getCurrentRequest();

        if (!$request) {
            return null;
        }

        return array_map(static function ($element) {
                // Multipart form values will be encoded in JSON.
                $decoded = json_decode($element, true);

                return \is_array($decoded) ? $decoded : $element;
            }, $request->request->all()) + $request->files->all();
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDecoding($format): bool
    {
        return self::FORMAT === $format;
    }
}