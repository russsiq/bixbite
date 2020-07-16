<?php declare(strict_types=1);

namespace Tests\Feature\App\View\Components\Widgets;

// Тестируемый класс.
use App\View\Components\Widgets\TagsCloud;

// Сторонние зависимости.
use App\Models\Tag;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\View\Components\Widgets\TagsCloud
 */
class TagsCloudTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        $this->afterApplicationCreated(function () {
            // Добавляем пространство имен шаблонов активной темы.
            $this->app->view->addLocation(theme_path('views'));
        });

        parent::setUp();
    }

    /**
     * @test
     *
     * Пользователь можеть наблюдать пустое облако тегов.
     * @return void
     */
    public function testUserCanViewEmptyTagsCloud(): void
    {
        $component = $this->resolveComponentForTesting();

        $component->disableCache()
            ->resolveView()
            ->with($component->data())
            ->render(function (ViewContract $view, string $contents) {
                $variables = $view->getData();

                // $this->assertArrayHasKey('tags', $variables);
                $tags = $variables['tags'];
                $this->assertTrue($tags()->isEmpty());
            });
    }

    /**
     * Извлечь экземпляр Компонента для тестирования.
     * @return TagsCloud
     */
    protected function resolveComponentForTesting(): TagsCloud
    {
        return $this->app->make(TagsCloud::class);
    }
}
