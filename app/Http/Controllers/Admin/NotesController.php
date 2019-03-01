<?php

namespace BBCMS\Http\Controllers\Admin;

use BBCMS\Models\Note;
use BBCMS\Http\Requests\Admin\NoteRequest;
use BBCMS\Http\Controllers\Admin\AdminController;

class NotesController extends AdminController
{
    protected $model;
    protected $template = 'notes';

    public function __construct(Note $model)
    {
        parent::__construct();
        $this->authorizeResource(Note::class);

        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notes = $this->model
            ->where('user_id', user('id'))
            ->paginate(10);

        return $this->makeResponse('index', compact('notes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->makeResponse('create', [
            'note' => [],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \BBCMS\Http\Requests\Admin\NoteRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NoteRequest $request)
    {
        $note = auth()->user()->notes()->create($request->all());

        return $this->makeRedirect(true, 'admin.notes.index', __('msg.store'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \BBCMS\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function show(Note $note)
    {
        return $this->makeResponse('show', compact('note'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \BBCMS\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function edit(Note $note)
    {
        return $this->makeResponse('edit', compact('note'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \BBCMS\Http\Requests\Admin\NoteRequest  $request
     * @param  \BBCMS\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function update(NoteRequest $request, Note $note)
    {
        $note->update($request->all());

        return $this->makeRedirect(true, 'admin.notes.index', __('msg.update'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \BBCMS\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function destroy(Note $note)
    {
        if (! $note->is_completed) {
            return $this->makeRedirect(false, 'admin.notes.index', __('msg.error'));
        }

        $note->delete();

        return $this->makeRedirect(true, 'admin.notes.index', __('msg.destroy'));
    }
}
