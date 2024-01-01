<?php
namespace Cl\Log;

use Cl\Log\Exception\InvalidContextException;

trait LoggerMessageTrait
{
    const PH_OPEN_TAG = '{{';
    const PH_CLOSE_TAG = '}}';

    const PH_VALID_PATTERN = '/[^A-za-z0-9_\.]/';
    /**
     * Interpolates context values into the message placeholders.
     * 
     * @param string $message message string
     * @param array  $context context
     * 
     * @return string
     */
    function interpolate($message, ?array $context = []): string
    {
        $replace = [];
        foreach ($context as $key => $val) {
            if (!is_array($val) && (!is_object($val) || method_exists($val, '__toString'))) {
                $replace = array_merge($replace, $this->getReplacementForString($key, (string)$val));
                //$replace['{' . $key . '}'] = (string)$val;
            }
        }
        return strtr($message, $replace);
    }

    /**
     * Check if "exception" key contains Throwable object
     *
     * @param array $context 
     * 
     * @return \Throwable|null
     * @throws InvalidContextException
     */
    protected function assertContextException(array $context): \Throwable|null
    {
        
        if (isset($context['exception'])) {
            if (!$context['exception'] instanceof \Throwable) {
                throw new InvalidContextException(
                    sprintf(
                        'The context`s "exception" key must be type of %s. %s was given',
                        \Throwable::class,
                        get_debug_type($context['exception'])
                    )
                );
            }
            return $context['exception'];
        }
        return null;
    }

    protected function getReplacementString(string $placeholder, string|\Stringable $replacement)
    {
        return match (true) {
            preg_match($placeholder, static::PH_VALID_PATTERN) => 
                throw new InvalidContextException(
                    sprintf(
                        'The context placeholder "%s" contains unsupported symbols. A-Z a-z 0-9 "_" and "." are allowed only',
                        $placeholder
                    )
                ),
            default => [static::PH_OPEN_TAG.$placeholder.static::PH_CLOSE_TAG => (string)$replacement],
        };
    }

}