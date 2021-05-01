<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\Note\Store as StoreNoteRequest;
use App\Http\Requests\Api\V1\Note\Update as UpdateNoteRequest;
use App\Http\Resources\NoteCollection;
use App\Http\Resources\NoteResource;
use App\Models\Note;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotesController extends ApiController
{
    /**
     * Дополнение к карте сопоставления
     * методов ресурса и методов в классе политик.
     *
     * @var array
     */
    protected $advancedAbilityMap = [
        'form' => 'create',

    ];

    /**
     * Массив дополнительных методов, не имеющих
     * конкретной модели в качестве параметра класса политик.
     *
     * @var array
     */
    protected $advancedMethodsWithoutModels = [
        'form',

    ];

    /**
     * Создать экземпляр контроллера.
     */
    public function __construct()
    {
        $this->authorizeResource(Note::class, 'note');
    }

    /**
     * Отобразить весь список сущностей,
     * включая связанные сущности.
     *
     * @param Request  $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $notes = Note::with([
            'attachments',
            'user:users.id,users.name',
        ])
            ->where('user_id', $request->user()->id)
            ->get()
            ->append('image');

        $collection = new NoteCollection($notes);

        return $collection->response()
            ->setStatusCode(JsonResponse::HTTP_PARTIAL_CONTENT);
    }

    /**
     * Отобразить форму для создания сущности.
     * Позволяет передавать мета-данные и
     * связи с другими сущностями.
     *
     * @param Request  $request
     * @return JsonResponse
     */
    public function form(Request $request)
    {
        $note = new Note();
        $note->user = $request->user();
        $note->user_id = $note->user->id;

        $resource = new NoteResource($note);

        return $resource->response()
            ->setStatusCode(JsonResponse::HTTP_OK);
    }

    /**
     * Создать и сохранить сущность в хранилище.
     *
     * @param  StoreNoteRequest  $request
     * @return JsonResponse
     */
    public function store(StoreNoteRequest $request)
    {
        $note = Note::create($request->validated());

        $resource = new NoteResource($note);

        return $resource->response()
            ->setStatusCode(JsonResponse::HTTP_CREATED);
    }

    /**
     * Отобразить сущность.
     *
     * @param  Note  $note
     * @return JsonResponse
     */
    public function show(Note $note)
    {
        $note->load([
            'attachments',
            'user',
        ]);

        $resource = new NoteResource($note);

        return $resource->response()
            ->setStatusCode(JsonResponse::HTTP_OK);
    }

    /**
     * Обновить сущность в хранилище.
     *
     * @param  UpdateNoteRequest  $request
     * @param  Note  $note
     * @return JsonResponse
     */
    public function update(UpdateNoteRequest $request, Note $note)
    {
        $note->update($request->validated());

        $note->load([
            // 'attachments',
            // 'user',
        ]);

        $resource = new NoteResource($note);

        return $resource->response()
            ->setStatusCode(JsonResponse::HTTP_ACCEPTED);
    }

    /**
     * Удалить сущность из хранилища.
     *
     * @param  Note  $note
     * @return JsonResponse
     */
    public function destroy(Note $note)
    {
        $note->delete();

        return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
