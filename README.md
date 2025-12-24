<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).



migration

php artisan make:migration create_customers_table 
php artisan migrate

to delete all database php artisan migrate:rollback

php artisan make:factory CustomerFactory php artisan make:model Customer

php artisan db:seed

 php artisan storage:link
php artisan lang:publish

 php artisan make:model event_type -c
 php artisan make:model Role -c
 
php artisan storage:link php artisan lang:publish

php artisan make:controller namecontroller --resource route::resource("student", StudentController::class)

soft delete in model use SoftDeletes;

in database add column deleted_at

User::withTrashed()->get(); User::onlyTrashed()->get(); User::onlyTrashed()->restore(); User::withTrashed()->find(1)->restore(); User::withTrashed()->find(1)->forceDelete();

conditional activation class="{{ Route::is("dashboard.patient")?"active":"" }}" class="{{ request()->is("dashboard/admin")?"active":"" }}"

search $customers = Customer::when($request->search, function($query) use($request) { return $query->whereAny([ "name", "email", "id", "phone" ], "LIKE" , "%".$request->search."%" );

    })->orderBy("id", "desc")->paginate(8);
{{ $customers->appends(request()->query())->links() }}

// // Basic SELECT * FROM users // $users = DB::table('users')->get();

// // Selecting specific columns
// $users = DB::table('users')->select('id', 'name', 'email')->get();

// // WHERE clause
// $users = DB::table('users')->where('status', 'active')->get();

// // WHERE with multiple conditions
// $users = DB::table('users')
//     ->where('status', 'active')
//     ->where('role', 'admin')
//     ->get();

// // OR WHERE
// $users = DB::table('users')
//     ->where('status', 'active')
//     ->orWhere('role', 'admin')
//     ->get();

// // WHERE IN
// $users = DB::table('users')
//     ->whereIn('role', ['admin', 'editor', 'user'])
//     ->get();

// // WHERE NOT IN
// $users = DB::table('users')
//     ->whereNotIn('role', ['guest', 'banned'])
//     ->get();

// // WHERE BETWEEN
// $users = DB::table('orders')
//     ->whereBetween('total', [100, 500])
//     ->get();

// // WHERE NOT BETWEEN
// $users = DB::table('orders')
//     ->whereNotBetween('total', [100, 500])
//     ->get();

// // ORDER BY
// $users = DB::table('users')->orderBy('created_at', 'desc')->get();

// // GROUP BY with COUNT
// $counts = DB::table('orders')
//     ->select('status', DB::raw('COUNT(*) as count'))
//     ->groupBy('status')
//     ->get();

// // LIMIT and OFFSET (Pagination)
// $users = DB::table('users')->limit(10)->offset(20)->get();

// // JOIN
// $users = DB::table('users')
//     ->join('profiles', 'users.id', '=', 'profiles.user_id')
//     ->select('users.*', 'profiles.bio')
//     ->get();

// // LEFT JOIN
// $users = DB::table('users')
//     ->leftJoin('profiles', 'users.id', '=', 'profiles.user_id')
//     ->select('users.*', 'profiles.bio')
//     ->get();

// // RIGHT JOIN
// $users = DB::table('users')
//     ->rightJoin('profiles', 'users.id', '=', 'profiles.user_id')
//     ->select('users.*', 'profiles.bio')
//     ->get();

// // EXISTS
// $exists = DB::table('users')->where('email', 'john@example.com')->exists();

// // DOESN'T EXIST
// $notExists = DB::table('users')->where('email', 'nonexistent@example.com')->doesntExist();

// // INSERT
// DB::table('users')->insert([
//     'name' => 'John Doe',
//     'email' => 'john@example.com',
//     'password' => bcrypt('password')
// ]);

// // INSERT GET ID
// $id = DB::table('users')->insertGetId([
//     'name' => 'Jane Doe',
//     'email' => 'jane@example.com',
//     'password' => bcrypt('password')
// ]);

// // UPDATE
// DB::table('users')
//     ->where('id', $id)
//     ->update(['email' => 'jane.doe@example.com']);

// // INCREMENT
// DB::table('users')->where('id', $id)->increment('login_attempts');

// // DECREMENT
// DB::table('users')->where('id', $id)->decrement('login_attempts');

// // DELETE
// DB::table('users')->where('id', $id)->delete();

// // RAW QUERIES
// $users = DB::select("SELECT * FROM users WHERE status = ?", ['active']);

php artisan make:controller RoleController --resource