<?php

declare(strict_types=1);

namespace Http\Document;

use Tests\TestCase;

/**
 * Class EncodingIndexTest
 * @package Http\Document
 */
class EncodingIndexTest extends TestCase
{
    /**
     * The Encoding Index shall be displayed as a document.
     *
     * @return void
     */
    public function testView()
    {
        $response = $this->get(route('encoding.index'));

        $response->assertViewIs('document');
    }
}
