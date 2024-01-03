<?php

namespace Cl\Log\Test;

use Cl\Log\AbstractLogger;
use Cl\Log\Message\Exception\InvalidContextException;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

use Stringable;
use Exception;

/**
 * @covers Cl\Log\AbstractLogger
 */
class AbstractLoggerTest extends TestCase
{

    public static LoggerInterface|null $logger = null;
    public static object|null          $contextObject = null;
    public static array|null           $contextScalar = null;


    public function __construct($name)
    {
        parent::__construct($name);
        $this->setUp();
    }
    /**
     * Set up test data
     *
     * @return void
     */
    public function setUp(): void
    {

        // Init logger using abstractLogger
        static::$logger = new class extends AbstractLogger {
            /**
             * Make the interpolate() method public
             *
             * @param string     $message 
             * @param array|null $context 
             * 
             * @return string
             */
            public function publicInterpolate(string $message, ?array $context = []): string
            {
                return $this->interpolateMessage(message: $message, context: $context);
            }

            /**
             * Null logger
             *
             * @param mixed             $level 
             * @param string|Stringable $message 
             * @param array             $context 
             * 
             * @return void
             */
            public function log(mixed $level, string|Stringable $message, ?array $context = []): void
            {
                //null
            }
        };

        // Scalar context
        static::$contextScalar = [
            'string' => 'Hello',
            'number' => 42,
            'boolean' => true,
            'stringable' => new class {
                public function __toString(): string
                {
                    return "Stringable";
                }
            },
            'stringableWithException' => new class {
                public function __toString(): string
                {
                    throw new Exception('__toString() with exception');
                }
            },
        ];

        // Init object context
        static::$contextObject = new class {
            public    $publicProperty = 'Public property';
            protected $proectedProperty = 'Protected property';
            private   $_privateProperty = 'Private property';

            public object $subObject;

            public function __construct()
            {
                $this->subObject = new class {
                    public string $stringSubProperty = 'string sub property';
                };
            }
            public function stringableMethod(): string
            {
                return 'Stringable method';
            }
            public function scalarMethod(): mixed
            {
                return true;
            }
            public function objectMethod(): object
            {
                return new class {
                    public mixed $objectMethodScalarProperty = 'Object method scalar property';
                };
            }
        };


    }
    
    /**
     *
     * @return void
     */
    public function testInterpolateMessage(): void
    {
        $message ='Scalar values: {string} {number} {boolean} {stringable}';
        $context = static::$contextScalar;

        $result = static::$logger->publicInterpolate($message, $context);

        $this->assertEquals('Scalar values: Hello 42 true Stringable', $result);
    }

    // /**
    //  *
    //  * @return void
    //  */
    // public function testInterpolateStringableWithException(): void
    // {
    //     $this->expectException(InvalidContextException::class);

    //     $message ='Scalar values: {string} {number} {boolean} {stringable} {stringableWithException}';
    //     $context = static::$contextScalar;

    //     $result = static::$logger->publicInterpolate($message, $context);
    // }

    /**
     *
     * @return void
     */
    public function testInterpolateObjectProperties(): void
    {
        $message ='Obect property: public: {object.publicProperty}';

        $context = [
            'object' => static::$contextObject,
        ];
        $result = static::$logger->publicInterpolate($message, $context);

        $this->assertEquals('Obect property: public: Public property', $result);
    }
    
    /**
     *
     * @return void
     */
    public function testInterpolateObjectNonPublicProperties(): void
    {
        $message ='Obect property: public: {object.publicProperty} {object.protectedProperty} {object._privateProperty}';

        $context = [
            'object' => static::$contextObject,
        ];
        $result = static::$logger->publicInterpolate($message, $context);

        $this->assertEquals('Obect property: public: Public property  ', $result);
    }

    /**
     *
     * @return void
     */
    public function testInterpolateSubObjectProperties(): void
    {
        $message ='Sub obect property: {object.subObject.stringSubProperty}';

        $context = [
            'object' => static::$contextObject,
        ];
        $result = static::$logger->publicInterpolate($message, $context);

        $this->assertEquals('Sub obect property: string sub property', $result);
    }

    /**
     *
     * @return void
     */
    public function testInterpolateSubObjectNotExistsProperties(): void
    {
        $message = 'Sub obect property: {object.notExistsProperty} {object.subObject.stringSubProperty.subsub}';

        $context = [
            'object' => static::$contextObject,
        ];
        $result = static::$logger->publicInterpolate($message, $context);

        $this->assertEquals('Sub obect property:  ', $result);
    }

    /**
     *
     * @return void
     */
    public function testInterpolateWithScalarsAndObjectProperties(): void
    {
        $message ='Scalar values: {string} {number} {boolean} {stringable};
            Object property: {object.publicProperty};
            Sub object property: {object.subObject.stringSubProperty}';

        $context = static::$contextScalar;
        $context['object'] = static::$contextObject;
        $result = static::$logger->publicInterpolate($message, $context);

        $this->assertEquals(
            'Scalar values: Hello 42 true Stringable;
            Object property: Public property;
            Sub object property: string sub property',
            $result
        );
    }

    /**
     *
     * @return void
     */
    public function testInterpolateArray(): void
    {
        $message ='Array offset: {array.1} {array.two}';

        $context = [
            'array' => [1=>"one", "two" => "second"]
        ];
        $result = static::$logger->publicInterpolate($message, $context);

        $this->assertEquals('Array offset: one second', $result);
    }

    /**
     *
     * @return void
     */
    public function testInterpolateSubArray(): void
    {
        $message ='Array offset: {array.1} {array.two} {array.3.forth}';

        $context = [
            'array' => [1=>"one", "two" => "second", 3=>['forth'=>'four']]
        ];
        $result = static::$logger->publicInterpolate($message, $context);

        $this->assertEquals('Array offset: one second four', $result);
    }

    /**
     *
     * @return void
     */
    public function testInterpolateArrayWithNotExistsOffset(): void
    {
        $message = 'Array offset: {array.notExists} {array.4}';

        $context = [
            'array' => [1=>"one", "two" => "second"]
        ];
        $result = static::$logger->publicInterpolate($message, $context);

        $this->assertEquals('Array offset:  ', $result);
    }
}
