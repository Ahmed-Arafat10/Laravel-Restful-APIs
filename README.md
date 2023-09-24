````php

+----------+-----------------------------------------------+----------------------------------------+
| Method   | URI                                           | Action                                 |
+----------+-----------------------------------------------+----------------------------------------+
| GET      | buyers                                        | BuyerController@index                  |
 GET      | buyers/{buyer}                                | BuyerController@show                   |
| GET      | buyers/{buyer}/categories                     | BuyerCategoryController@index          |
| GET      | buyers/{buyer}/products                       | BuyerProductController@index           |
| GET      | buyers/{buyer}/sellers                        | BuyerSellerController@index            |
| GET      | buyers/{buyer}/transactions                   | BuyerTransactionController@index       |
| POST     | categories                                    | CategoryController@store               |
| GET      | categories                                    | CategoryController@index               |
| PUT|PATCH| categories/{category}                         | CategoryController@update              |
| DELETE   | categories/{category}                         | CategoryController@destroy             |
| GET      | categories/{category}                         | CategoryController@show                |
| GET      | categories/{category}/buyers                  | CategoryBuyerController@index          |
| GET      | categories/{category}/products                | CategoryProductController@index        |
| GET      | categories/{category}/sellers                 | CategorySellerController@index         |
| GET      | categories/{category}/transactions            | CategoryTransactionController@index    |
| GET      | products                                      | ProductController@index                |
| GET      | products/{product}                            | ProductController@show                 |
| GET      | products/{product}/buyers                     | ProductBuyerController@index           |
| POST     | products/{product}/buyers/{buyer}/transactions| ProductBuyerTransactionController@store|
| GET      | products/{product}/categories                 | ProductCategoryController@index        |
| DELETE   | products/{product}/categories/{category}      | ProductCategoryController@destroy      |
| PUT|PATCH| products/{product}/categories/{category}      | ProductCategoryController@update       |
| GET      | products/{product}/transactions               | ProductTransactionController@index     |
| GET      | sellers                                       | SellerController@index                 |
| GET      | sellers/{seller}                              | SellerController@show                  |
| GET      | sellers/{seller}/buyers                       | SellerBuyerController@index            |
| GET      | sellers/{seller}/categories                   | SellerCategoryController@index         |
| GET      | sellers/{seller}/products                     | SellerProductController@index          |
| POST     | sellers/{seller}/products                     | SellerProductController@store          |
| DELETE   | sellers/{seller}/products/{product}           | SellerProductController@destroy        |
| PUT|PATCH| sellers/{seller}/products/{product}           | SellerProductController@update         |
| GET      | sellers/{seller}/transactions                 | SellerTransactionController@index      |
| GET      | transactions                                  | TransactionController@index            |
| GET      | transactions/{transaction}                    | TransactionController@show             |
| GET      | transactions/{transaction}/categories         | TransactionCategoryController@index    |
| GET      | transactions/{transaction}/sellers            | TransactionSellerController@index      |
| POST     | users                                         | UserController@store                   |
| GET      | users                                         | UserController@index                   |
| GET      | users/verify/{token}                          | UserController@verify                  |
| DELETE   | users/{user}                                  | UserController@destroy                 |
| PUT|PATCH| users/{user}                                  | UserController@update                  |
| GET      | users/{user}                                  | UserController@show                    |
| GET      | users/{user}/resend                           | UserController@resend                  |
+---------+-----------------------------------------------+----------------------------------------+	
````

- learned topics
  - mutators
  - accessors
  - `has()` method like `$buyer = Buyer::has('transactions')->findOrFail($id);`
  - `whereHas()`
  - `isDirty()` and `isClean()`
  - traits usage (when iam not able to extend from another class as this class already extends from another)
  - usage of `public function render($request, Throwable $e)` in `app/Exceptions/Handler`
  - `$modelName = class_basename($e->getModel()); // App\\Models\\User --> User`
  - `$this->errorResponse("Model {$modelName} Not Found", 404);`
  - `ValidationException`
  - `ModelNotFoundException`
  - `AuthenticationException`
  - `AuthorizationException`
  - `NotFoundHttpException` : when you access a not exist URL
  - `MethodNotAllowedHttpException` :  when a request needs GET and you send POST request
  - `HttpException` : any other possible exception
  - `QueryException` : error in database query
  - implicit model binding : when you pass a `User $user` model instead of just `$id` in a method like `Show($id)`
  - `config('app.debug')` :  to access debug key in app file
  - it is a good practicing to access `config()` in your code not `app()` 
  - `Route::resource('categories',CategoryController::class)
    ->except(['create', 'edit']);`
  - `Route::resource('buyers', BuyerController::class)
    ->only(['index', 'show']);`
  - `Route::resource('buyer',BuyerController::class,[
    'except' => ['create','update','destroy'],
    'parameters' => ['buyer' => 'buyerID'],
    'middleware' => ['auth'],
    'prefix' => 'admin',
    ]);`
  - `'email' => 'email|' . Rule::unique('users', 'email')->ignore($user->id)` Or `unique:users,email,' . $user->id`
  - `'admin' => Rule::in([User::ADMIN_USER, User::REGULAR_USER])` Or `'admin' => 'in:' . User::ADMIN_USER . ',' . User::REGULAR_USER`
  - `if ($request->has('name'))`
  - `Str::random(40);`
  - Global scope in Models
  - ` $table->softDeletes();//deleted_at`
  - `use SoftDeletes;` : to be able to use soft delete
  - two types of middleware one that deals with a request & the other deals with response
  - `laravel fractal` package (Transformers)
  - Method spoofing ( use `_method` as `PUT`, `PATCH` or `DELETE` while it is `POST`)
  - `private function isWebBased($request)
    {
    return $request->acceptsHtml() && collect($request->route()->middleware())->contains('web');
    }`
