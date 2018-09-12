[![Build Status](https://travis-ci.org/qiuyuhome/laravel-tdd.svg?branch=master)](https://travis-ci.org/qiuyuhome/laravel-tdd)

# TDD 的使用方式

参考:

* https://oomusou.io/refactor/refactor-in-action
* https://laravel-china.org/docs/forum-in-laravel-tdd



## 第一节

```shell
$ php artisan make:model Thread -mr
```

> 会同时生成Thread.php模型文件，ThreadController.php控制器文件，{timestamp}_create_threads_table.php迁移文件。

> 注：该命令生成控制器时，应修改为复数形式，如 app\Http\Controllers\ThreadsController.php

## 第二节

`laradock` 自带了 `sqlite`, 无需编译就可使用.

### TDD 的 3 条原则
1. 在编写失败的测试之前，不要编写任何业务代码；
2. 只要有一个单元测试失败了，就不要再些测试代码。无法通过编译也是一种失败情况；
3. 业务代码恰好能够让当前失败的测试成功通过即可，不要多写；

### 我的理解

了解需求后, 直接写 feature 测试, 这个时候, 其他的都先不考虑, 例如, 表, 类, 都下不管, 对应 3 条原则的第一条.

运行测试, 那肯定是失败的. 这个时候, 就对应 3 条原则的第二条, 只要把测试通过了. 这个需求就算完成了.

期间, 会有多次重构, 要结合单元测试. 比如说加了一个公共方法, 那么, 这个方法肯定要先写单元测试. 然后实现.