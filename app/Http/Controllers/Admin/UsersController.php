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
        $users = $this->model
            ->withCount('articles', 'comments')
            ->paginate();

        return $this->makeResponse('index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->makeResponse('create', [
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

        return $this->makeRedirect(true, 'admin.users.index', __('store'));
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
        return $this->makeResponse('edit', [
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
        $user->update($request->all());

        return $this->makeRedirect(true, 'admin.users.index', __('update'));
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

            return $this->makeRedirect(true, 'admin.users.index', __('destroy'));
        }
        catch (\Exception $e) {
            \DB::rollback();

            return $this->makeRedirect(false, 'admin.users.index', $e->getMessage());
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
                // if (! $users->update(['approve' => 1])) {
                //     $messages[] = '!mass_activate';
                // }
                break;
            case 'mass_lock':
                // if (! $users->update(['approve' => 0])) {
                //     $messages[] = '!mass_lock';
                // }
                break;
            case 'mass_delete':
                $usersTemp = $users->get();
                foreach ($usersTemp as $user) {
                    if (! $user->delete()) {
                        $messages[] = '!mass_delete';
                    }
                }
                break;
            case 'mass_delete_inactive':
                // if (! $users->update(['on_mainpage' => 1])) {
                //     $messages[] = '!mass_delete_inactive';
                // }
                break;
        }

        $message = empty($messages) ? 'msg.complete' : 'msg.complete_but_null';

        return $this->makeRedirect(true, 'admin.users.index', $message);
    }
}
