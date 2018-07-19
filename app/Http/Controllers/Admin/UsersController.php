<?php

namespace BBCMS\Http\Controllers\Admin;

use BBCMS\Models\User;
use BBCMS\Http\Requests\Admin\UserRequest;
use BBCMS\Http\Requests\Admin\UsersRequest;
use BBCMS\Http\Controllers\Admin\AdminController;

class UsersController extends AdminController
{
    protected $model;
    protected $roles;
    protected $x_fields;
    protected $template = 'users';

    public function __construct(User $model)
    {
        parent::__construct();
        $this->authorizeResource(User::class);

        $this->model = $model;
        $this->roles = cache('roles');
        $this->x_fields = $model->x_fields;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->renderOutput('index', [
            'users' => $this->model->withCount('articles', 'comments')->paginate(10),
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
            'user' => [],
            'roles' => $this->roles,
            'x_fields' => $this->x_fields,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \BBCMS\Http\Requests\Admin\UserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $this->model->fill($request->all())->save();

        return redirect()->route('admin.users.index')->withStatus('store!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \BBCMS\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \BBCMS\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return $this->renderOutput('edit', [
            'user' => $user,
            'roles' => $this->roles,
            'x_fields' => $this->x_fields,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \BBCMS\Http\Requests\Admin\UserRequest  $request
     * @param  \BBCMS\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user)
    {
        $user->fill($request->all())->save();

        return redirect()->route('admin.users.index')->withStatus('Update!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \BBCMS\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        try {
            \DB::beginTransaction();
            $user->delete();
            \DB::commit();

            return redirect()->route('admin.users.index')->withStatus('no destroy!');
        }
        catch (\Exception $e) {
            \DB::rollback();

            return redirect()->back()->withErrors([$e->getMessage()]);
        }
    }

    /**
     * Mass update the specified resource in storage.
     *
     * @param  \BBCMS\Http\Requests\Admin\UsersRequest  $request
     * @param  \BBCMS\Models\User  $users
     * @return \Illuminate\Http\Response
     */
    public function massUpdate(UsersRequest $request, User $users)
    {
        $this->authorize('otherUpdate', User::class);

        $data = $request->except('_token', 'updated_at', 'image', 'submit');
        $users = $users->whereIn('id', $data['users']);
        $messages = [];

        switch ($data['mass_action']) {
            case 'mass_activate':
                if (! $users->update(['approve' => 1])) {
                    $messages[] = '!mass_activate';
                }
                break;
            case 'mass_lock':
                if (! $users->update(['approve' => 0])) {
                    $messages[] = '!mass_lock';
                }
                break;
            case 'mass_delete_inactive':
                if (! $users->update(['on_mainpage' => 1])) {
                    $messages[] = '!mass_delete_inactive';
                }
                break;
            case 'mass_delete':
                $usersTemp = $users->get();
                foreach ($usersTemp as $user) {
                    /*// Check if user has his own photo or avatar
                    if ( (trim($urow['avatar'])) and (file_exists($config['avatars_dir'].$urow['photo'])) )
                        @unlink($config['avatars_dir'].$urow['avatar']);*/
                    if (! $user->delete()) {
                        $messages[] = '!mass_delete';
                    }
                }
                break;
        }

        if (count($messages)) {
            return redirect()->back()->withErrors($messages)->withInput();
        } else {
            return redirect()->route('admin.users.index')->withStatus($data['mass_action']);
        }
    }
}
