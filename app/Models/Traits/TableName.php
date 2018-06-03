<?php

namespace App\Models\Traits;

/**
 * Trait TableName
 */
trait TableName
{
    /**
     * Get table name
     *
     * @return string table name
     */
    public static function getTableName() : string
    {
        $class = self::class;

        return with(new $class())->getTable();
    }
}
