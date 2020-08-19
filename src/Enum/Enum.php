<?php

declare(strict_types=1);

namespace N1215\Php8Attributes\Enum;

use BadMethodCallException;
use InvalidArgumentException;
use JsonSerializable;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionClassConstant;
use ReflectionException;
use RuntimeException;
use Stringable;

/**
 * Enumのベース
 * @packase N1215\Php8Attributes\Enum
 */
abstract class Enum implements JsonSerializable, Stringable
{
    /**
     * スカラー値
     */
    private int|string $value;

    /**
     * 定数の値のリスト
     * @var int[]|string[]
     */
    protected static array $constantsList = [];

    /**
     * インスタンスのキャッシュ
     * @var static[][]
     */
    protected static array $instancePool = [];

    /**
     * テキスト
     */
    protected string $text;

    /**
     * コンストラクタ
     * @param int|string $value
     */
    protected function __construct(int|string $value)
    {
        $values = static::values();
        $constantKey = array_search($value, $values);
        if ($constantKey === false) {
            $message = sprintf('%s は %sに定義されていない値です', $value, static::class);
            throw new InvalidArgumentException($message);
        }

        $this->value = $value;

        $this->initialize($constantKey, $value);
    }

    /**
     * 新規作成
     * @param int|string $value
     * @return static
     */
    public static function of(int|string $value): static
    {
        $values = static::values();
        $constantKey = array_search($value, $values);
        if ($constantKey === false) {
            $message = sprintf('%s は %sに定義されていない値です', $value, static::class);
            throw new InvalidArgumentException($message);
        }

        // インスタンスのキャッシュから取得
        if (!isset(static::$instancePool[static::class][$constantKey])) {
            if (!isset(static::$instancePool[static::class])) {
                static::$instancePool[static::class] = [];
            }
            static::$instancePool[static::class][$constantKey] = new static($values[$constantKey]);
        }

        return static::$instancePool[static::class][$constantKey];
    }

    /**
     * 全Enumの配列を取得
     * @return static[]
     */
    public static function all(): array
    {
        $values = array();

        foreach (static::values() as $key => $value) {
            $values[$key] = static::of($value);
        }

        return $values;
    }

    /**
     * 値の連想配列を取得
     * @return int[]|string[]
     */
    public static function values(): array
    {
        $class = static::class;

        if (isset(static::$constantsList[$class])) {
            return static::$constantsList[$class];
        }

        try {
            $ref = new ReflectionClass($class);
        } catch (ReflectionException $e) {
            throw new RuntimeException('クラス定数の取得に失敗しました', 0, $e);
        }

        static::$constantsList[$class] = $ref->getConstants();
        return static::$constantsList[$class];
    }

    /**
     * セレクトボックスへの表示用配列
     * @return string[]
     */
    public static function getOptions(): array
    {
        $enums = static::all();
        $options = array_combine(
            array_map(
                fn (Enum $value) => $value->getValue(),
                $enums
            ),
            array_map(
                fn (Enum $value) => $value->getText(),
                $enums
            ),
        );
        assert($options !== false);
        return $options;
    }

    /**
     * @param string $constantKey
     * @param array $args
     * @return static
     */
    public static function __callStatic($constantKey, $args)
    {
        $values = static::values();
        if (!array_key_exists($constantKey, $values)) {
            $message = sprintf('%s は %sに定義されていないキーです', $constantKey, static::class);
            throw new BadMethodCallException($message);
        }

        $value = $values[$constantKey];

        return static::of($value);
    }

    /**
     * 初期化処理
     * テキストを設定
     * @param string $constantKey
     * @param int|string $value
     */
    protected function initialize(string $constantKey, int|string $value): void
    {
        $reflectionConstant = new ReflectionClassConstant(static::class, $constantKey);

        $attributes = $reflectionConstant->getAttributes();

        $textAttributes = array_filter(
            $attributes,
            fn (ReflectionAttribute $attribute) => $attribute->getName() === Text::class
        );

        if (count($textAttributes) === 0) {
            $this->text = (string) $value;
            return;
        }

        $this->text = $textAttributes[0]->getArguments()[0];
    }

    /**
     * @return int|string
     */
    public function getValue(): int|string
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * 対象と一致するかどうかを返す
     * @param Enum $another
     * @return bool
     */
    public function equals(Enum $another): bool
    {
        if (static::class !== $another::class) {
            throw new InvalidArgumentException('引数は' . static::class . 'クラスのオブジェクトである必要があります。');
        }
        return $this->value === $another->value;
    }

    /**
     * 文字列に変換
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->value;
    }

    /**
     * JSONに変換
     * @return int|string
     */
    public function jsonSerialize(): int|string
    {
        return $this->getValue();
    }
}
