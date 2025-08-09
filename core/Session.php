<?php

namespace Core;

class Session
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function __destruct()
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
        }
    }

    public function set(string $key, mixed $value): void
    {
        $segments = explode('.', $key);
        $ref = &$_SESSION;

        foreach ($segments as $segment) {
            if (!isset($ref[$segment]) || !is_array($ref[$segment])) {
                $ref[$segment] = [];
            }
            $ref = &$ref[$segment];
        }

        $ref = $value;
    }

    public function get(string $key, $default = null): mixed
    {
        $segments = explode('.', $key);
        $ref = $_SESSION;

        foreach ($segments as $segment) {
            if (!isset($ref[$segment])) {
                return $default;
            }

            $ref = $ref[$segment];
        }

        return $ref;
    }

    public function delete(string ...$keys): void
    {
        foreach ($keys as $key) {
            $segments = explode('.', $key);
            $ref = &$_SESSION;

            foreach ($segments as $i => $segment) {
                if (!isset($ref[$segment])) {
                    break;
                }

                if ($i === count($segments) - 1) {
                    unset($ref[$segment]);
                    break;
                }

                $ref = &$ref[$segment];
            }
        }
    }

    public function has(string $key): bool
    {
        $segments = explode('.', $key);
        $ref = $_SESSION;

        foreach ($segments as $segment) {
            if (!isset($ref[$segment])) {
                return false;
            }

            $ref = $ref[$segment];
        }

        return true;
    }

    public function destroy(): void
    {
        $_SESSION = [];
        session_destroy();
    }
}
