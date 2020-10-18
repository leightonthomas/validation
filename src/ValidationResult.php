<?php

declare(strict_types=1);

namespace Validation;

use function array_key_exists;
use function array_pop;
use function array_shift;
use function count;
use function explode;
use function join;
use function rtrim;
use function var_dump;

/**
 * @psalm-template T
 */
final class ValidationResult
{

    /**
     * @var array
     * @psalm-var list<string>
     */
    private array $currentPath;

    /**
     * @var array
     * @psalm-var array<string, list<string>>
     */
    private array $errors;

    /**
     * @var mixed
     * @psalm-var T
     */
    private $value;

    /**
     * @var bool
     * @psalm-var bool
     */
    private bool $valid;

    /**
     * @param mixed $value
     * @param string[][] $errors
     *
     * @psalm-param array<string, list<string>> $errors
     *
     * @return self
     * @psalm-return self<T>
     */
    public static function withErrors($value, array $errors): self
    {
        $instance = new self($value);

        foreach ($errors as $key => $keyErrors) {
            $instance->errors[$key] = $keyErrors;
        }

        $instance->valid = count($errors) <= 0;

        return $instance;
    }

    /**
     * @param mixed $value
     * @psalm-param T $value
     */
    public function __construct($value)
    {
        $this->value = $value;
        $this->valid = true;
        $this->currentPath = [];
        $this->errors = [];
    }

    /**
     * @return mixed
     * @psalm-return T
     */
    public function getValue()
    {
        return $this->value;
    }

    public function addToPath(string $path): void
    {
        $this->currentPath[] = $path;
    }

    public function removeLastPath(): void
    {
        array_pop($this->currentPath);
    }

    public function addError(string $error): void
    {
        $this->errors[join(".", $this->currentPath)][] = $error;
        $this->valid = false;
    }

    public function getErrorsByPath(): array
    {
        return $this->errors;
    }

    public function getErrors(): array
    {
        $errors = [];

        foreach ($this->errors as $pathStr => $errorMessages) {
            $path = explode(".", (string) $pathStr);
            if ($pathStr === "") {
                array_shift($path);
            }

            $root = &$errors;
            foreach ($path as $pathPart) {
                if (! array_key_exists($pathPart, $root)) {
                    $root[$pathPart] = [];
                }

                $root = &$root[$pathPart];
            }

            foreach ($errorMessages as $msg) {
                $root[] = $msg;
            }
        }

        return $errors;
    }

    public function isValid(): bool
    {
        return $this->valid;
    }

    public function mergeAtCurrentPath(self $other): void
    {
        $currentPath = join(".", $this->currentPath);

        foreach ($other->errors as $newPath => $newErrors) {
            if ($currentPath === '') {
                $fullPath = $newPath;
            } else {
                $fullPath = $currentPath;
            }

            if ($currentPath !== '' && $newPath !== '') {
                $fullPath = "{$currentPath}.{$newPath}";
            }

            foreach ($newErrors as $newError) {
                $this->errors[$fullPath][] = $newError;
            }
        }

        $this->valid = $this->valid && $other->valid;
    }
}
