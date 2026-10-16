<?php

class Site
{
    public static function tous(): array
    {
        return db()->query('SELECT id, nom, ville FROM sites ORDER BY nom')->fetchAll();
    }
}
