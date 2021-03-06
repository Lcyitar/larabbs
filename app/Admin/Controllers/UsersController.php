<?php

namespace App\Admin\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class UsersController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('用户列表 练习')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('用户详情 练习')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('修改用户')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('创建用户')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User);

        $grid->id('Id');
        $grid->avatar('头像')->image(config('app.url'), 50, 50);
        $grid->name('姓名');
        $grid->phone('电话');
        $grid->email('邮箱');
        $grid->created_at('注册时间');
        $grid->last_actived_at('最后活跃时间');

        $grid->model()->orderBy('id', 'desc');

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(User::findOrFail($id));

        $show->id('Id');
        $show->avatar('头像')->image(config('app.url'), 50, 50);
        $show->name('姓名');
        $show->phone('电话');
        $show->email('邮箱');
        $show->weixin_openid('微信 openid');
        $show->weixin_unionid('微信 unionid');
        $show->created_at('注册时间');
        $show->updated_at('更新时间');
        $show->introduction('简介');
        $show->last_actived_at('最后活跃时间');


        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new User);

        $form->text('name', '姓名')->rules('required|between:3,25');
        $form->mobile('phone', '电话');
        $form->email('email', '邮箱')->rules(function ($form) {

            return 'required|unique:users,email,' . $form->model()->id;

        });
        $form->password('password', '密码')->placeholder('输入 重置的密码');
        $form->image('avatar', '头像')->move('/uploads/images/avatars/');
        $form->text('introduction', '简介');

        $form->saving(function (Form $form) {
            if ($form->password) {
                $form->password = bcrypt($form->password);
            }
        });


        return $form;
    }
}