<?php

if (!function_exists('parseVersion')) {
    /**
     * Convert a version string to generalized version string.
     *
     * @param string  $version
     *
     * @return string
     */
    function parseVersion(string $version): string
    {
        return preg_replace('/[^0-9.]/', '', $version);
    }
}

if (!function_exists('getMajorVersion')) {
    /**
     * @param string|int  $version
     *
     * @return int
     */
    function getMajorVersion(string|int $version): int
    {
        return (int) explode('.', parseVersion($version))[0];
    }
}

if (!function_exists('getVersionId')) {
    /**
     * @param string  $version
     *
     * @return int
     */
    function getVersionId(string $version): int
    {
        [$major, $minor, $patch] = explode('.', parseVersion($version));

        return (int) $major .
            str_pad($minor, 3, 0, STR_PAD_LEFT) .
            str_pad($patch, 3, 0, STR_PAD_LEFT);
    }
}
