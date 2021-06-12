<?php

namespace App\Actions\Attachment;

use App\Contracts\Actions\Attachment\CreatesAttachment;
use App\Models\Attachment;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CreateAttachmentAction extends AttachmentActionAbstract implements CreatesAttachment
{
    protected $stopOnFirstFailure = true;

    /**
     * Validate and create a newly attachment.
     *
     * @param  array  $input
     * @return Attachment
     */
    public function create(array $input): Attachment
    {
        $this->authorize(__FUNCTION__, Attachment::class);

        $this->ensureIncomingDataHasValidFile($input);

        $data = $this->gatherData($input[Attachment::UPLOADED_FILE]);

        $validated = $this->validate(
            $this->prepareForValidation($input, $data)
        );

        $this->attachment = Attachment::getModel()
            ->manageUpload($input[Attachment::UPLOADED_FILE], $validated);

        return $this->attachment;
    }

    /**
     * Gather data for the given uploaded file.
     *
     * @param  UploadedFile  $uploadedFile
     * @return array
     */
    public function gatherData(UploadedFile $uploadedFile): array
    {
        $data = [
            'filesize' => $uploadedFile->getSize(),
            'extension' => $this->detectExtension($uploadedFile),
            'mime_type' => $uploadedFile->getMimeType(),
            'original_title' => pathinfo($uploadedFile->getClientOriginalName(), \PATHINFO_FILENAME),
        ];

        $data['type'] = $this->getFileType($data['mime_type'], $data['extension']);

        return $data;
    }

    /**
     * Prepare the data for validation.
     *
     * @param  array  $input
     * @param  array  $data
     * @return array
     */
    protected function prepareForValidation(array $input, array $data): array
    {
        $input['user_id'] = $this->user() ? $this->user()->getAuthIdentifier() : null;
        $input['attachable_id'] = $input['attachable_id'] ?? null;
        $input['attachable_type'] = $input['attachable_type'] ?? null;
        $input['disk'] = $input['disk'] ?? 'public';
        $input['folder'] = $input['folder'] ?? 'uploads';
        $input['title'] = $input['title'] ?? $data['original_title'] ?? Str::random(8);
        $input['description'] = Str::cleanHTML($input['description'] ?? null);

        // Unstable data.
        $input['type'] = $data['type'];
        $input['filesize'] = $data['filesize'];
        $input['extension'] = $data['extension'];
        $input['mime_type'] = $data['mime_type'];
        $input['name'] = Str::slug($input['title']).'_'.time();

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
            $this->conditionFileRules(), // Only when create.
            $this->userIdRules(), // Only when create.
            $this->attachableTypeRules(), // Only when create.
            $this->attachableIdRules(), // Only when create.
            $this->titleRules(),
            $this->descriptionRules(),
            $this->diskRules(), // Only when create.
            $this->folderRules(), // Only when create.
            $this->typeRules(), // Only when create.
            $this->nameRules(),
            $this->extensionRules(), // Only when create.
            $this->mimeTypeRules(), // Only when create.
            $this->filesizeRules(), // Only when create.
            // $this->propertiesRules(), // Not used.
            // $this->downloadsRules(),
        );
    }

    /**
     * Determine the file extension by its content.
     *
     * @param  UploadedFile  $uploadedFile
     * @return string
     */
    protected function detectExtension(UploadedFile $uploadedFile): string
    {
        if (is_null($expected = $uploadedFile->guessExtension())) {
            // There is no point in uploading empty files.
            throw ValidationException::withMessages([
                Attachment::UPLOADED_FILE => $this->translate(
                    $uploadedFile->getErrorMessage()
                ),
            ]);
        }

        $expected = strtolower($expected);
        $original = strtolower($uploadedFile->getClientOriginalExtension());

        if ('mpga' === $expected && 'mp3' === $original) {
            $expected = 'mp3';
        } elseif ('xml' === $expected && 'fb2' === $original) {
            $expected = 'fb2';
        }

        if ($expected !== $original) {
            throw ValidationException::withMessages([
                Attachment::UPLOADED_FILE => $this->translate(
                    'A mismatch was found between the content [:expected] of the file and its extension [:original].',
                    compact('expected', 'original')
                ),
            ]);
        }

        if ('jpg' === $expected) {
            $expected = 'jpeg';
        }

        return $expected;
    }

    /**
     * Get the file type for cataloging by content.
     *
     * @param  string  $mimeType
     * @param  string  $extension
     * @return string
     */
    protected function getFileType(string $mimeType, string $extension): string
    {
        foreach ($this->allowedFileTypes as $type => $definition) {
            if (in_array($extension, $definition['extension']) || in_array($mimeType, $definition['mime_type'])) {
                return $type;
            }
        }

        return 'other';
    }

    /**
     * Make sure the incoming data contains a valid uploaded file.
     *
     * @param  array  $data
     * @return void
     *
     * @throws ValidationException
     */
    protected function ensureIncomingDataHasValidFile(array $data): void
    {
        $uploadedFile = $data[Attachment::UPLOADED_FILE] ?? null;

        $successfulOutcome = $uploadedFile instanceof UploadedFile
            && $uploadedFile->isValid();

        if (! $successfulOutcome) {
            throw ValidationException::withMessages([
                Attachment::UPLOADED_FILE => $this->translate(
                    'validation.file', [
                        'attribute' => Attachment::UPLOADED_FILE,
                    ]
                ),
                // Attachment::UPLOADED_FILE => $uploadedFile->getErrorMessage(),
            ]);
        }

        if (Attachment::UNKNOWN_MIME_TYPE === $uploadedFile->getMimeType()) {
            throw ValidationException::withMessages([
                Attachment::UPLOADED_FILE => $this->translate(
                    'File type :EXTENSION (:mime_type) is not supported.', [
                        'extension' => $uploadedFile->getClientOriginalExtension(),
                        'mime_type' => $uploadedFile->getMimeType(),
                    ]
                ),
            ]);
        }
    }
}
