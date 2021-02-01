<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Articles;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Тестирование удаления ресурса `Article`.
 *
 * @cmd vendor/bin/phpunit Tests\Feature\Api\V1\Articles\DeleteArticleResourceTest.php
 */
class DeleteArticleResourceTest extends TestCase
{
    use RefreshDatabase;
}
