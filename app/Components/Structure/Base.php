<?php

namespace App\Components\Structure;

class Base
{
    protected static function validate($validation, $path, $value)
    {
        $validation = explode(':', $validation, 2);

        switch ($validation[0]) {
        case 'any':
            break;
        case 'nullable':
            if ($value === null) {
                return;
            }

            if (count($validation) < 2) {
                return;
            }
            array_shift($validation);

            return static::validate(implode(':', $validation), $path, $value);
            break;
        case 'boolean':
            if ($value !== (bool) $value) {
                throw new \InvalidArgumentException("Unexpected non-boolean value for spec value ${path}");
            }
            break;
        case 'integer':
            if (! is_int($value)) {
                throw new \InvalidArgumentException("Unexpected non-integer value for spec value ${path}");
            }
            break;
        case 'float':
            if (! is_float($value)) {
                throw new \InvalidArgumentException("Unexpected non-num value for spec value ${path}");
            }
            break;
        case 'number':
            if (! (is_float($value) || is_int($value))) {
                throw new \InvalidArgumentException("Unexpected non-num value for spec value ${path}");
            }
            break;
        case 'string':
            if (! is_string($value)) {
                throw new \InvalidArgumentException("Unexpected non-string value for spec value ${path}");
            }
            array_shift($validation);

            if (count($validation) > 0) {
                $validation = explode(':', $validation[0]);
            }
            while (count($validation) > 0) {
                $one_validation = array_shift($validation);

                if (ctype_digit($one_validation)) {
                    if (strlen($value) > (int) $one_validation) {
                        throw new \InvalidArgumentException("Unexpected long-string value for spec value ${path} (> ${one_validation})");
                    }

                    continue;
                }

                $decoded = @json_decode($one_validation);

                if (is_array($decoded)) {
                    if (! in_array($value, $decoded)) {
                        throw new \InvalidArgumentException("Unexpected value for spec value ${path} (not one of: ".implode(', ', $decoded).')');
                    }
                } else {
                    throw new \LogicException("(internal error) Unknown validation type {$one_validation}");
                }
            }
            break;
        case 'array':
            if (! is_array($value)) {
                throw new \InvalidArgumentException("Unexpected non-array value for spec value ${path}");
            }

            if (count($validation) > 1) {
                foreach ($value as $key => $subvalue) {
                    static::validate($validation[1], $path.'.'.$key, $subvalue);
                }
            }
            break;
        case 'object':
            if (! is_object($value) && ! is_array($value)) {
                throw new \InvalidArgumentException("Unexpected non-object value for spec value ${path}");
            }
            $value = (array) $value;

            array_shift($validation);

            if (count($validation) > 0) {
                $decoded = @json_decode($validation[0]);

                if (! is_object($decoded) || count((array) $decoded) !== 1) {
                    throw new \LogicException("(internal error) Unknown non-object subvalidation for ${path}");
                }
                $key_validation = array_keys((array) $decoded);
                $key_validation = $key_validation[0];

                $value_validation = array_values((array) $decoded);
                $value_validation = $value_validation[0];
                foreach ($value as $key => $subvalue) {
                    static::validate($key_validation, $path.'.('.$key.')', $key);
                    static::validate($value_validation, $path.'.'.$key, $subvalue);
                }
            }
            break;
        case 'datetime':
            if (! ($value instanceof \DateTime)) {
                throw new \InvalidArgumentException("Unexpected non-DateTime value for spec value ${path}");
            }
            break;
        default:
            throw new \LogicException("(internal error) Unknown validation type {$validation[0]}");
        }
    }

    public function __construct($expect, $spec)
    {
        $found = [];
        $spec  = (array) $spec;
        foreach ($spec as $key => $value) {
            if (! isset($expect[$key])) {
                throw new \InvalidArgumentException("Unknown spec key ${key}");
            }

            static::validate($expect[$key], $key, $value);
            $found[$key] = true;

            $this->{str_replace('-', '_', $key)} = $value;
        }

        foreach ($expect as $key => $validation) {
            if (! array_key_exists($key, $spec)) {
                throw new \InvalidArgumentException("Missing spec key ${key}");
            }
        }
    }
}
