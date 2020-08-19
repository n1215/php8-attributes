<?php

declare(strict_types=1);

namespace N1215\Php8Attributes\Enum;

/**
 * @method static static APPLE()
 * @method static static ORANGE()
 * @method static static PEACH()
 */
class Fruit extends Enum
{
    @@Text('りんご🍎')
    private const APPLE = 'apple';

    @@Text('みかん🍊')
    private const ORANGE = 'orange';

    @@Text('もも🍑')
    private const PEACH = 'peach';
}
