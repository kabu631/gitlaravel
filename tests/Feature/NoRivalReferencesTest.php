<?php

namespace Tests\Feature;

use Tests\TestCase;

/** Seed content is written by us. Rival gadget sites in the Nepal market must not be named, credited or linked. */
class NoRivalReferencesTest extends TestCase
{
    public function test_seed_content_does_not_mention_rival_gadget_sites(): void
    {
        $files = array_merge(
            glob(database_path('seeders/*.php')),
            glob(database_path('seeders/data/*/*.php')),
            glob(database_path('seeders/data/*/*.txt')),
        );
        $pattern = '/gadget ?byte|nepali ?telecom|game ?shop ?nepal|techlekh|gadgets ?in ?nepal\.com/i';

        $hits = array_filter($files, fn ($file) => preg_match($pattern, file_get_contents($file)));

        $this->assertSame([], array_map('basename', array_values($hits)));
    }
}
