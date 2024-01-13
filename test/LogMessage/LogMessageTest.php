<?php

namespace Cl\Log\Test;

use Cl\Log\Message\Exception\InvalidArgumentException;
use Cl\Log\Message\LogMessage;
use Cl\Log\PsrLogLevelEnum;
use PHPUnit\Framework\TestCase;

use Stringable;
use Exception;
use Psr\Log\LogLevel;
use RuntimeException;

/**
 * @covers Cl\Log\Message\LogMessage
 */
class LogMessageTest extends TestCase
{

    public static LogMessage|null $logMessage = null;
    public static object|null     $contextObject = null;
    public static array|null      $contextScalar = null;


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
        static::$logMessage = new LogMessage();

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

    public function getMessageTextWithLogLevel(string $logLevel, $message)
    {
        return sprintf('%s %s', $logLevel, $message);
    }
    
    /**
     *
     * @return void
     */
    public function testInterpolateWithScalars(): void
    {
        $message ='{Scalar values: {string} {number} {boolean} {stringable}}';
        $context = static::$contextScalar;

        $result = static::$logMessage->set(LogLevel::INFO, $message, $context)->get();

        $this->assertEquals(
            $this->getMessageTextWithLogLevel(LogLevel::INFO, '{Scalar values: Hello 42 true Stringable}'),
            $result
        );
    }

    /**
     *
     * @return void
     */
    public function testInterpolateStringableWithException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $message ='Scalar values: {string} {number} {boolean} {stringable} {stringableWithException}';
        $context = static::$contextScalar;

        $result = static::$logMessage->set(LogLevel::INFO, $message, $context)->get();
    }

    /**
     *
     * @return void
     */
    public function testInterpolateObjectProperties(): void
    {
        $message ='Obect property: {public: {object.publicProperty}}';

        $context = [
            'object' => static::$contextObject,
        ];
        $result = static::$logMessage->set(LogLevel::INFO, $message, $context)->get();

        $this->assertEquals(
            $this->getMessageTextWithLogLevel(LogLevel::INFO, 'Obect property: {public: Public property}'),
            $result
        );
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
        $result = static::$logMessage->set(LogLevel::DEBUG, $message, $context)->get();

        $this->assertEquals(
            $this->getMessageTextWithLogLevel(LogLevel::DEBUG, 'Sub obect property: string sub property'),
            $result
        );
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
        $result = static::$logMessage->set(LogLevel::NOTICE, $message, $context)->get();

        $this->assertEquals(
            $this->getMessageTextWithLogLevel(LogLevel::NOTICE, 'Sub obect property: {object.notExistsProperty} {object.subObject.stringSubProperty.subsub}'),
            $result
        );
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
        $result = static::$logMessage->set(LogLevel::INFO, $message, $context)->get();

        $this->assertEquals(
            $this->getMessageTextWithLogLevel(
                LogLevel::INFO, 
            'Scalar values: Hello 42 true Stringable;
            Object property: Public property;
            Sub object property: string sub property'
        ),
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
        $result = static::$logMessage->set(LogLevel::WARNING, $message, $context)->get();

        $this->assertEquals(
            $this->getMessageTextWithLogLevel(LogLevel::WARNING, 'Array offset: one second'),
            $result
        );
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
        $result = static::$logMessage->set(LogLevel::INFO, $message, $context)->get();

        $this->assertEquals(
            $this->getMessageTextWithLogLevel(LogLevel::INFO, 'Array offset: one second four'),
            $result
        );
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
        $result = static::$logMessage->set(LogLevel::INFO, $message, $context)->get();

        $this->assertEquals(
            $this->getMessageTextWithLogLevel(LogLevel::INFO, 'Array offset: {array.notExists} {array.4}'),
            $result
        );
    }
}
