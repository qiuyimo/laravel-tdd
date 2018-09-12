[![Build Status](https://travis-ci.org/qiuyuhome/laravel-tdd.svg?branch=master)](https://travis-ci.org/qiuyuhome/laravel-tdd)

# TDD 的使用方式

参考:

* https://oomusou.io/refactor/refactor-in-action
* https://laravel-china.org/docs/forum-in-laravel-tdd



## 第一节 Initial Database Setup With Seeding

### 控制器文件使用单数还是复数?

**原文**

```shell
$ php artisan make:model Thread -mr
```

> 会同时生成Thread.php模型文件，ThreadController.php控制器文件，{timestamp}_create_threads_table.php迁移文件。

> 注：该命令生成控制器时，应修改为复数形式，如 app\Http\Controllers\ThreadsController.php

**我的理解**

这里我不同意, 根据官网的设定. 使用 `php artisan make:model Thread -mr` 命令生成 `controller` 是单数命名. 那么就应该使用单数的形式. 规则是这样的. 

- 控制器名单数，对应的路由复数
- model是单数，对应的表是复数

<<Laravel 项目开发规范>> 中虽然提 `controller` 使用复数形式. 但是, 却未被了最基本的 [开发哲学](https://laravel-china.org/docs/laravel-specification/5.5/whats-the-use-of-standards/510):

其中的第二条, 优先选择框架提倡的做法, 既然官方使用的是单数的形式. 那么就不要再做自定义了. 使用起来也方便. 难道每次使用命令行生成的控制器都要再手动改一次? **NO**.

### 数据填充

**原文**

进入`tinker`环境：

```php
$ php artisan tinker
```

执行以下语句，填充假数据：

```php
>>> $threads = factory('App\Reply',50)->create();
```

**我的理解**

个人习惯问题. 我更习惯写入 `seeder` 中. 

```shell
$ php artisan make:seeder ReplySeeder
```

```php
<?php

use Illuminate\Database\Seeder;

class ReplySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory('App\Reply', 50)->create();
    }
}

```

然后只要运行命令 `php artisan db:seed class=ReplySeeder` 就可以生成数据了. 以后再次生成数据也可以再次利用.

## 第二节 Testing Drving Threads

### 测试环境的配置

*phpunit.xml*

```php
<php>
    <env name="DB_CONNECTION" value="sqlite"/>
    <env name="DB_DATABASE" value=":memory:"/>
</php>
```

测试环境可以使用 `sqlite`. 方便快捷. 新学到的技巧.

`laradock` 自带了 `sqlite`, 无需编译就可使用. 舒服.

### TDD 的 3 条原则

**Bob Martin** describes Test-Driven Development using these three simple rules:

1. Do not write production code unless it is to make a failing unit test pass.
2. Do not write more of a unit test than is sufficient to fail, and build failures are failures.
3. Do not write more production code than is sufficient to pass the one failing unit test.

看不懂. 中文如下:

1. 除非为了使一个失败的unit test通过，否则不允许编写任何产品代码.
2. 在一个单元测试中只允许编写刚好能够导致失败的内容（编译错误也算失败）.
3. 只允许编写刚好能够使一个失败的unit test通过的产品代码.

**我的理解**

了解需求后, 直接写 feature 测试, 这个时候, 其他的都先不考虑, 例如, 表, 类, 都下不管, 对应 3 条原则的第一条.

运行测试, 那肯定是失败的. 这个时候, 就对应 3 条原则的第二条, 只要把测试通过了. 这个需求就算完成了.

期间, 会有多次重构, 要结合单元测试. 比如说加了一个公共方法, 那么, 这个方法肯定要先写单元测试. 然后实现.

当然, 这个是理想状态, 在原文中, 多次看到作者修改了最开始写的测试代码. 所以, 只要达到目的就可以. 毕竟, 经验不丰富和需求不明确的前提下, 很多东西写到了才知道具体的细节.

### SQL 排序技巧

> 注：`latest()` 和 `oldest()` 方法允许你轻松地按日期对查询结果排序。默认情况下是对 created_at 字段进行排序。或者，你可以传递你想要排序的字段名称：

```php
// 获取倒序的数据.
Thread::latest()->get();

// 获取最后一条数据.
$user = DB::table('users')->latest()->first();

// 获取第一条数据.
$user = DB::table('users')->oldest()->first();
```



### 测试文件设置

都要使用 trait `DatabaseMigrations`

```php
<?php

namespace Illuminate\Foundation\Testing;

use Illuminate\Contracts\Console\Kernel;

trait DatabaseMigrations
{
    /**
     * Define hooks to migrate the database before and after each test.
     *
     * @return void
     */
    public function runDatabaseMigrations()
    {
        $this->artisan('migrate:fresh');

        $this->app[Kernel::class]->setArtisan(null);

        $this->beforeApplicationDestroyed(function () {
            $this->artisan('migrate:rollback');

            RefreshDatabaseState::$migrated = false;
        });
    }
}

```

通过注释可以看出, 在测试文件中使用这个 trait 后, 每次都会初始化数据库, 测试结束后会回滚. 如果不这样, 每次的测试数据都保留在数据库中, 对下一次的测试可能会造成影响. 

### 功能测试

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_browse_threads()
    {
        $thread = factory('App\Thread')->create();

        $response = $this->get('/threads');

        $response->assertSee($thread->title);
    }
}
```

从文中的代码可以看出. feature test 中的方法名, 都是用来描述一个具体的功能, 所以, 功能测试的出发点是用户(client).

这样, 如果需求不明确, 也写不出来. 起到了整理思路和消化需求的作用.

### Q&A

**Q:** 数据都是使用 factory 生成的. 没有任何一个地方是使用的数据库中原有的数据(想用也用不了, 因为 rollback 了), 是不是所有的单元测试都是这种方式呢? 

**A:** 暂时不知道.

## 第三节 A Thread Can Have Replies

### 功能测试可以先写伪代码

```php
/** @test */
public function a_user_can_read_replies_that_are_associated_with_a_thread()
{
   // 如果存在 Thread
   // 并且该 Thread 拥有回复
   // 那么当我们看该 Thread 时
   // 我们也要看到回复
}
```

这里可以看到. 先把伪代码写好. 然后再开始写功能测试. 让思路更清晰. 好习惯. 同时, 这个真的就可以用来作为代码的注释.

```php
/** @test */
public function a_user_can_read_replies_that_are_associated_with_a_thread()
{
    // 如果有 Thread
    // 并且该 Thread 有回复
    $reply = factory('App\Reply')->create(['thread_id' => $this->thread->id]);
    // 那么当我们看 Thread 时
    // 我们也要看到回复
    $this->get('/threads/'.$this->thread->id)->assertSee($reply->body);
}
```



### 测试代码重构了, 一定要第一时间运行全部测试.

测试代码写着写着, 会发现有很多可以优化和复用的地方. 但是, 要在当前的测试全部通过的前提下重构, 重构完毕, 要第一时间运行全部的测试, 保证通过.

### 功能测试实现期间, 可以写单元测试

功能测试做完, 就相当于把一个功能实现了. 但是, 一个功能一般会写很多方法, 这些方法就可以用单元测试来验证. 

例如: 写完了功能测试, 现在正在写代码让这个功能测试通过, 这个时候, 会写若干个单独的小的方法, 那么, 每次在写小的方法之前, 应该先写这些小的方法的单元测试. 把这个单元测试通过了, 再写下一个小的方法的单元测试. 再通过. 最后, 所有的单元测试都实现了, 功能测试也就实现了. 

### 新的知识

* Carbon 的使用






