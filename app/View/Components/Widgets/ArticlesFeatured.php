<?php

namespace App\View\Components\Widgets;

use App\Models\Article;
use App\Rules\TitleRule;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Russsiq\Widget\WidgetAbstract;

class ArticlesFeatured extends WidgetAbstract
{
    /** @var Article */
    protected $articleModel;

    /** @var Collection */
    protected $articleCollection;

    /**
     * Get the template name relative to the widgets directory.
     *
     * @var string
     */
    protected $template = 'components.widgets.articles-featured';

    /**
     * Create a new widget instance.
     *
     * @param array $parameters
     */
    public function __construct(Article $articleModel, array $parameters = [])
    {
        $this->articleModel = $articleModel;

        parent::__construct($parameters);
    }

    /**
     * Get Article Collection.
     *
     * @return Collection
     */
    public function articles(): Collection
    {
        return $this->articleCollection
            ?? $this->resolveArticleCollection();
    }

    /**
     * Resolve Article Collection.
     *
     * @return Collection
     */
    protected function resolveArticleCollection(): Collection
    {
        return $this->articleCollection = $this->articleModel->query()
            ->latest('views')
            ->when(
                $this->parameters->getInt('limit'),
                function (Builder $query, int $limit) {
                    return $query->limit($limit);
                }
            )
            ->get();
    }

    /**
     * Get the validation rules that apply to the widget parameters.
     *
     * @return array
     */
    public function rules(TitleRule $titleRule): array
    {
        return [
            'title' => [
                'required',
                'string',
                'min:4',
                'max:255',
                $titleRule,
            ],
            'limit' => [
                'sometimes',
                'integer',
            ],
        ];
    }
}
