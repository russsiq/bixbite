<?php

namespace App\Actions\Attachment;

use App\Contracts\Actions\Attachment\UpdatesAttachment;
use App\Models\Attachment;
use Illuminate\Support\Str;

class UpdateAttachmentAction extends AttachmentActionAbstract implements UpdatesAttachment
{
    /**
     * Validate and update the given attachment.
     *
     * @param  Attachment  $attachment
     * @param  array  $input
     * @return Attachment
     */
    public function update(Attachment $attachment, array $input): Attachment
    {
        $this->authorize(
            __FUNCTION__, $this->attachment = $attachment
        );

        $this->attachment->update(
            $this->validate(
                $this->prepareForValidation($input)
            )
        );

        return $this->attachment;
    }

    /**
     * Prepare the data for validation.
     *
     * @param  array  $input
     * @param  array  $data
     * @return array
     */
    protected function prepareForValidation(array $input): array
    {
        // $input['disk'] = $input['disk'] ?? 'public'; // Not released.
        // $input['folder'] = $input['folder'] ?? 'uploads'; // Not released.

        $input['title'] = $input['title'] ?? null;
        $input['description'] = Str::cleanHTML($input['description'] ?? null);

        if (! empty($input['title'])) {
            $input['name'] = Str::slug($input['title']).'_'.time();
        }

        return $input;
    }

    /**
     * Get the validation rules that apply to the action.
     *
     * @return array
     */
    protected function rules(): array
    {
        return array_merge(
            // $this->conditionFileRules(), // Only when create.
            // $this->userIdRules(), // Only when create.
            // $this->attachableTypeRules(), // Only when create.
            // $this->attachableIdRules(), // Only when create.
            $this->titleRules(),
            $this->descriptionRules(),
            // $this->diskRules(), // Only when create.
            // $this->folderRules(), // Only when create.
            // $this->typeRules(), // Only when create.
            $this->nameRules(),
            // $this->extensionRules(), // Only when create.
            // $this->mimeTypeRules(), // Only when create.
            // $this->filesizeRules(), // Only when create.
            // $this->propertiesRules(), // Only when create.
            // $this->downloadsRules(),
        );
    }
}
