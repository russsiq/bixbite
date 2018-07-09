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
        return $this->renderOutput('index', [
            'notes' => $this->model->where('user_id', user('id'))->paginate(10),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->renderOutput('create', [
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

        return redirect()->route('admin.notes.index')->withStatus('store!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \BBCMS\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function show(Note $note)
    {
        return $this->renderOutput('show', [
            'note' => $note,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \BBCMS\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function edit(Note $note)
    {
        return $this->renderOutput('edit', [
            'note' => $note,
        ]);
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
        $note->update($data = $request->all());

        return redirect()->route('admin.notes.index')->withStatus('store!');
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
            return redirect()->back()->withErrors(['error message']);
        }

        $note->delete();

        return redirect()->route('admin.notes.index')->withStatus('destroy!');
    }
}
