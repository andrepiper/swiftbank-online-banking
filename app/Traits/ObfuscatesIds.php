<?php

namespace App\Traits;

use App\Helpers\IdObfuscator;

trait ObfuscatesIds
{
    /**
     * Obfuscate an ID
     *
     * @param int $id
     * @return string
     */
    protected function obfuscateId($id)
    {
        return IdObfuscator::encode($id);
    }

    /**
     * Deobfuscate an ID
     *
     * @param string $obfuscatedId
     * @return int|null
     */
    protected function deobfuscateId($obfuscatedId)
    {
        return IdObfuscator::decode($obfuscatedId);
    }
}
