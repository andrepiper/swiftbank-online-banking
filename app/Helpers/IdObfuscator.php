<?php

namespace App\Helpers;

class IdObfuscator
{
    /**
     * Get the salt used for obfuscation
     */
    protected static function getSalt()
    {
        return config('helpers.id_obfuscation.salt', 'SwiftBank');
    }

    /**
     * Get the minimum length of the obfuscated ID
     */
    protected static function getMinLength()
    {
        return config('helpers.id_obfuscation.min_length', 8);
    }

    /**
     * Check if a string is a valid UUID/GUID
     *
     * @param string $str
     * @return bool
     */
    protected static function isUuid($str)
    {
        return preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $str) === 1;
    }

    /**
     * Obfuscate an ID
     *
     * @param mixed $id
     * @return string
     */
    public static function encode($id)
    {
        // If it's a UUID/GUID, return it as is
        if (self::isUuid($id)) {
            return $id;
        }

        // If it's not numeric, return as is
        if (!is_numeric($id)) {
            return $id;
        }

        // Convert ID to a base36 string (alphanumeric)
        $encoded = base_convert($id, 10, 36);

        // Add a hash component based on the ID and salt
        $hash = substr(md5($id . static::getSalt()), 0, 5);

        // Combine them
        $result = $encoded . $hash;

        // Ensure minimum length
        if (strlen($result) < static::getMinLength()) {
            $result = str_pad($result, static::getMinLength(), '0', STR_PAD_LEFT);
        }

        return $result;
    }

    /**
     * De-obfuscate an ID
     *
     * @param string $obfuscatedId
     * @return mixed
     */
    public static function decode($obfuscatedId)
    {
        if (empty($obfuscatedId)) {
            return null;
        }

        // If it's a UUID/GUID, return it as is
        if (self::isUuid($obfuscatedId)) {
            return $obfuscatedId;
        }

        // Check if the ID has a hash component (should be at least 5 characters for the hash)
        if (strlen($obfuscatedId) < 5) {
            return $obfuscatedId; // Too short to have a hash, return as is
        }

        // Extract the encoded part (everything except the last 5 characters which is the hash)
        $encoded = substr($obfuscatedId, 0, -5);

        // If the encoded part is empty, return the original ID
        if (empty($encoded)) {
            return $obfuscatedId;
        }

        // Try to convert back to base10
        try {
            $id = base_convert($encoded, 36, 10);

            // Verify the hash to ensure it hasn't been tampered with
            $hash = substr(md5($id . static::getSalt()), 0, 5);
            $providedHash = substr($obfuscatedId, -5);

            if ($hash !== $providedHash) {
                return $obfuscatedId; // Hash doesn't match, return original ID
            }

            return (int) $id;
        } catch (\Exception $e) {
            // If conversion fails, return the original ID
            return $obfuscatedId;
        }
    }
}
