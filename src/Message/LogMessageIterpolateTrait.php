<?php
declare(strict_types=1);
namespace Cl\Log\Message;

use Cl\Log\Message\Exception\InvalidContextException;
use Cl\Log\Message\Exception\InvalidRegexException;
use Stringable;

trait LogMessageIterpolateTrait
{
    /**
     * Interpolates context values into the message placeholders.
     * 
     * @return LogMessageInterface
     */
    public function interpolate(): LogMessageInterface
    {
        // Ensure "exception" key is checked for Throwable
        $this->assertContextValidException();

        $replace = [];
        foreach ($this->getMessagePlaceholders() as $placeholder) {
            try {
                $replacement = $this->getReplacement($placeholder, $this->context);
            } catch (\Throwable $e) {
                // PSR-3:
                // "A given value in the context MUST NOT throw an exception nor raise any php error, warning or notice"

                $replacement = ''; // After exception catch the Placeholder will be removed from the string

                //-------------------------------------------------//
                /* Keep to figure out about throwing forward or logging */
                
                // throw new InvalidContextException(
                //     sprintf(
                //         'An error occured during conversion to string: %s',
                //         $e->getMessage()
                //     ),
                //     $e->getCode(),
                //     $e
                // );
            }

            if (is_string($replacement)) {
                // Merge replacements for each context item
                $replace += [
                    LogMessageInterface::PLACEHOLDER_OPEN_TAG . $placeholder . LogMessageInterface::PLACEHOLDER_CLOSE_TAG  => $replacement
                ];
            }
        }
        // Perform the string replacement in the message
        $this->processedMessage = strtr($this->message, $replace);

        return $this;
    }

    /**
     * Get placeholder regex using to split message to placeholders
     *
     * @return string
     */
    protected function getPlaceholderRegex(): string
    {
        $openTag = LogMessageInterface::PLACEHOLDER_OPEN_TAG;
        $closeTag = LogMessageInterface::PLACEHOLDER_CLOSE_TAG;
        return "/\\{$openTag}([^{$closeTag}]+)\\{$closeTag}/sumix";
    }

    /**
     * Check if placeholder contains valid symbols
     *
     * @param string $placeholder 
     * 
     * @return LogMessageInterface
     */
    protected function assertValidPlaceholder(string $placeholder): LogMessageInterface
    {
        if (preg_match($placeholder, LogMessageInterface::INVALID_PLACEHOLDER_REGEX_PATTERN)) {
            throw new InvalidContextException(
                sprintf(
                    'The context placeholder "%s" contains unsupported symbols. A-Z a-z 0-9 "_" and "." are allowed only',
                    $placeholder
                )
            );
        }
        return $this;
    }

    /**
     * Check if "exception" key contains Throwable object
     *
     * @return LogMessageInterface
     * @throws InvalidContextException
     */
    protected function assertContextValidException(): LogMessageInterface
    {
        $this->contextException = null;
        
        
        if (isset($this->context['exception'])) {

            $this->contextException = match (true) {

                // Context contains correct exception object
                $this->context['exception'] instanceof \Throwable => $this->context['exception'],

                // Context contains invalid exception value
                default => throw new InvalidContextException(
                    sprintf(
                        'The context`s "exception" key must be type of %s. %s was given',
                        \Throwable::class,
                        get_debug_type($this->contextException)
                    )
                )
            };
        } 
            
        return $this;
    }

    /**
     * Find all placeholders in the message
     * 
     * @return array 
     * @throws InvalidRegexException if there is a regex error
     */
    protected function getMessagePlaceholders(): array
    {
        try {
            if (preg_match_all($this->getPlaceholderRegex(), $this->message, $matches, PREG_SET_ORDER)) {
                return array_column($matches, 1);
            }
            return [];
        } catch (\Exception $e) {
            throw new InvalidRegexException('Error in regex pattern: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Check if value is stringable and return its string value
     *
     * @param mixed $value 
     * 
     * @return string
     */
    protected function getScalarReplacement(mixed $value, bool $dump = true): string
    {
            return match (true) {
                // Scalar or Stringable
                is_string($value),
                is_numeric($value),
                (is_object($value) && method_exists($value, '__toString'))  => (string)$value,

                // Boolean
                is_bool($value) => $value ? 'true' : 'false',

                // any other types
                default => $dump ? var_export($value, true) : get_debug_type($value),
            };
    }

    /**
     * Get the replacement for a placeholder
     *
     * @param string $placeholder 
     *      The placeholder. Placeholder can have format with subaccess: "placeholder.sub.sub.sub..."
     *      Gives access to object's public properties: "someObject.publicProperty..."
     *      Gives access to array's including both number and string keys: "someArray.7.stringKey..."
     * 
     * @param mixed  $context     
     *      The context with actual values
     * 
     * @param bool   $dump        
     *      Indicates if return dump of the value 
     *      if it was not converted to the string or return type name
     * 
     * @return string The found replacement or empty string
     */
    protected function getReplacement(string $placeholder, mixed $context, bool $dump =false): string
    {
        $this->assertValidPlaceholder($placeholder);

        if (is_array($context) && isset($context[$placeholder])) {

            // Get a string from the scalar or stringable
            return $this->getScalarReplacement($context[$placeholder], $dump);
        }
        if (is_object($context) && property_exists($context, $placeholder)) {

            // Get a string the object property
            return $this->getScalarReplacement($context->{$placeholder}, $dump);
        }
        if (strpos($placeholder, '.') ) {
            
            // Go throw the sub placeholders
            $objectPlaceholder = strstr($placeholder, '.', true);

            // Get a sub context from the context
            $subContext = match (true) {

                // The context is object and has needle property
                is_object($context) && property_exists($context, $objectPlaceholder) => $context->{$objectPlaceholder},

                // The context is arra and has needle key
                is_array($context)  && isset($context[$objectPlaceholder])           => $context[$objectPlaceholder],

                // Not found
                default => false,
            };

            if ($subContext) {

                // Make the sub placeholder
                $subPlaceholder = trim(strstr($placeholder, '.'), '.');

                // Find a replacement recursively
                return $this->getReplacement($subPlaceholder, $subContext);
            }
        }
        // Nothing was found. Replace a placeholder with the empty string
        return '';
    }
}