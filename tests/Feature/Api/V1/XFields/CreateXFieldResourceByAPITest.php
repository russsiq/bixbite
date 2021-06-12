<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\XFields;

use App\Models\XField;
use App\Policies\XFieldPolicy;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\JsonResponse;
use Tests\Concerns\InteractsWithPolicy;
use Tests\Concerns\JsonApiTrait;
use Tests\Fixtures\XFieldFixtures;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Http\Controllers\Api\V1\XFieldsController
 *
 * @cmd vendor/bin/phpunit Tests\Feature\Api\V1\XFields\CreateXFieldResourceByAPITest.php
 *
 * Наблюдатель модели XField использует построителя схемы `\Illuminate\Database\Schema\Builder`,
 * который вызывает неявные фиксации БД,
 * что мешает полноценной работе трейта `\Illuminate\Foundation\Testing\RefreshDatabase`,
 * который пытается откатить транзакцию после каждого теста и генерирует:
 *
 * > PDOException: There is no active transaction
 *
 * **Решение**: использовать трейт `Illuminate\Foundation\Testing\DatabaseMigrations`,
 * запускаемый при каждом тестовом случае и не контролирующий транзакции БД.
 *
 * **Недостаток**: при каждой итерации тестовых случаев выполняются миграции и их откат –
 * тесты выполняются очень медленно.
 *
 * **Другие испробованные решения**: при использовании трейта `RefreshDatabase`
 * задавать в качестве значения пустой массив свойству `$connectionsToTransact` текущего класса –
 * не дает выполнять утверждения относительно содержимого в БД.
 */
class CreateXFieldResourceByAPITest extends TestCase
{
    use JsonApiTrait;
    use InteractsWithPolicy;
    use DatabaseMigrations;

    public const JSON_API_PREFIX = XField::TABLE;

    /**
     * @covers ::store
     * @cmd vendor\bin\phpunit --filter '::test_guest_cannot_create_x_field'
     */
    public function test_guest_cannot_create_x_field()
    {
        $response = $this->assertGuest()
            ->postJsonApi('store')
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    }

    /**
     * @covers ::store
     * @cmd vendor\bin\phpunit --filter '::test_user_without_ability_cannot_create_x_field'
     */
    public function test_user_without_ability_cannot_create_x_field()
    {
        $this->denyPolicyAbility(XFieldPolicy::class, ['create']);

        $user = $this->loginSPA();

        $response = $this->assertAuthenticated()
            ->postJsonApi('store')
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN);
    }

    /**
     * @covers ::update
     * @cmd vendor\bin\phpunit --filter '::test_user_with_ability_cannot_create_x_field_without_minimal_provided_data'
     */
    public function test_user_with_ability_cannot_create_x_field_without_minimal_provided_data()
    {
        $this->allowPolicyAbility(XFieldPolicy::class, ['create']);

        $user = $this->loginSPA();

        $response = $this->assertAuthenticated()
            ->postJsonApi('store')
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @covers ::store
     * @cmd vendor\bin\phpunit --filter '::test_super_admin_can_create_x_field_with_minimal_provided_data'
     */
    public function test_super_admin_can_create_x_field_with_minimal_provided_data()
    {
        $super_admin = $this->loginSuperAdminSPA();

        $data = [
            'extensible' => array_rand(array_flip(XField::extensibles())),
            'name' => 'in_stock',
            'type' => 'boolean',
            'title' => 'In stock',
        ];

        $expected = array_merge($data, [
            'name' => XField::getModel()->normalizeNameAttributePrefix($data['name']),
        ]);

        $this->assertDatabaseCount(XField::TABLE, 0);

        $response = $this->assertAuthenticated()
            ->postJsonApi('store', [], $data)
            // ->dump()
            ->assertStatus(JsonResponse::HTTP_CREATED)
            ->assertJsonPath('data.title', $expected['title'])
            ->assertJsonStructure(
                XFieldFixtures::resource()
            );

        $this->assertDatabaseHas(XField::TABLE, $expected);

        $schemaBuilder = $this->getConnection()->getSchemaBuilder();
        $doctrineSchemaManager = $this->getConnection()->getDoctrineSchemaManager();
        $doctrineSchemaManager->getDatabasePlatform()
            ->registerDoctrineTypeMapping('enum', 'string');

        $this->assertTrue(
            $schemaBuilder->hasColumn($data['extensible'], $expected['name'])
        );

        $this->assertSame(
            $data['type'],
            $schemaBuilder->getColumnType($data['extensible'], $expected['name'])
        );
    }
}
