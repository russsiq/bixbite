<?php

namespace BBCMS\Http\Controllers\Common;

use BBCMS\Http\Controllers\BaseController;
use BBCMS\Http\Requests\Common\ToggleRequest;

class ToggleController extends BaseController
{
    /**
     * Namespace to located models.
     *
     * @var string
     */
    protected $modelsNamespace = '\BBCMS\Models';

    public function attribute(ToggleRequest $reqest)
    {
        try {
            // Prepare local variables.
            $id = $reqest->id;
            $class = $this->modelsNamespace . '\\' . $reqest->model;
            $model = app($class);
            $attribute = $reqest->attribute;

            // Only castables attribute of boolean type may be toggled.
            if (! $model->hasCast($attribute, ['bool', 'boolean'])) {
                throw new \LogicException(sprintf(
                    'Attribute `%s` not defined in $casts of [%s] as boolean type.', $attribute, $class
                ));
            }

            // Find $item in this $model. Select all attribute to authorize.
            $item = $model->whereId($id)->firstOrFail();

            // Check permission to update $model $item.
            $this->authorize('update', $item);

            // Update attribute, if exists.
            $item->update([$attribute => ! $item->{$attribute}]);

            return redirect()->back()->withStatus(__('common.msg.complete'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
}
