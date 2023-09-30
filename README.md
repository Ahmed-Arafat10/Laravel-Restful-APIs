````php

+----------+-----------------------------------------------+----------------------------------------+
| Method   | URI                                           | Action                                 |
+----------+-----------------------------------------------+----------------------------------------+
| GET      | buyers                                        | BuyerController@index                  |
| GET      | buyers/{buyer}                                | BuyerController@show                   |
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
    - `with()` : eager loading
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
    - `'admin' => Rule::in([User::ADMIN_USER, User::REGULAR_USER])`
      Or `'admin' => 'in:' . User::ADMIN_USER . ',' . User::REGULAR_USER`
    - `if ($request->has('name'))`
    - `Str::random(40);`
    - Global scope in Models
    - ` $table->softDeletes();//deleted_at`
    - `use SoftDeletes;` : to be able to use soft delete
    - two types of middleware one that deals with a request & the other deals with response
      -  
    - `laravel fractal` package (Transformers)
    - Method spoofing ( use `_method` as `PUT`, `PATCH` or `DELETE` while it is `POST`)
    - `private function isWebBased($request)
      {
      return $request->acceptsHtml() && collect($request->route()->middleware())->contains('web');
      }`
    - New Factory & faker methods
    - check seeder file
    - `  return DB::transaction(function () use ($request, $product, $buyer) {
      $product->quantity -= $request->quantity;
      $product->save();
      $transaction = Transaction::create([
      'buyer_id' => $buyer->id,
      'product_id' => $product->id,
      'quantity' => $request->quantity
      ]);
      return $this->showOne($transaction, 201);
      });`

````php
        # will add the relationship each time (even if the relationship already exists between both product & category)
        $product->categories()->attach([$category->id]);
        # will remove all relationship between that product & other categories then add the new one
        $product->categories()->sync([$category->id]);
        # will not remove all relationship between that product & other categories then add the new one
        $product->categories()->syncWithoutDetaching([$category->id]);
````

- in `app` > `filesystem.php`

````php
        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
            'throw' => false,
        ],
        'myImg' => [
            'driver' => 'local',
            'root' => public_path('uploads'),
            'visibility' => 'public',
        ],
````

> `myImg` is custom key created by me

- use it like this

````php
$img = $request->file('image');
$img->store('img', 'myImg');
````

- to delete from it

````php
        Storage::disk('myImg')->delete('img/' . $product->image);
````

> note that `img` path is relative to `uploads` path that is root path in key `MyImg

- update image in `update()` method

````php
if ($request->hasFile('image')) {
    if (Storage::disk('myImg')->exists('img/' . $product->image))
        Storage::disk('myImg')->delete('img/' . $product->image);
    $img = $request->file('image');
    $img->store('img', 'myImg');
    $product->image = $img->hashName();
 }
````

- events & evens listener
- sending a data to mail view

- storage > logs > laravel.log

- in database seeder if we want to disable event listener (not to send email for every fake created user)

````php
User::flushEventListeners();
````

- dealing with Failing-Prone actions with `retry()` helper :

````php
  retry(5, function () use ($user) {
            event(new NewUserRegistered($user));
        }, 100);
````

> try 5 times between each 100 milliseconds then if fails throw an exception

- rate limiter in app > providers > `RouteServiceProvider.php`
````php
RateLimiter::for('api', function (Request $request) {
      return Limit::perMinute(40)->by($request->user()?->id ?: $request->ip());
});
````


- middleware
````php
class SignatureMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next, $headerName = 'X-Name'): Response
    {
        $response = $next($request);
        $response->headers->set($headerName, config('app.name'));
        return $response;
    }
}

````

````php
 protected $middlewareGroups = [
        'web' => [
            'http.signature:X-Application-Name',
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            'http.signature:X-Application-Name',
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            \Illuminate\Routing\Middleware\ThrottleRequests::class . ':api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];
````
> `'http.signature:X-Application-Name'`, this is the parameter sent to `handle()` method with `$headerName` parameter as ``X-Application-Name



- create markdown mailer class
````php
 php artisan make:mail test -m emails.test
````

- inside it
````php
public function content(): Content
    {
        return new Content(
            markdown: 'emails.test',
        );
    }
````

- to pass data
````php
   public function content(): Content
    {
        return new Content(
            markdown: 'emails.test', with: ['user' => $this->user]
        );

    }
````

- in created views > emails > test.blade.php
````php
<x-mail::message>
# Verify Your New Account

Please verify your new account by clicking on below button
hello {{$user->name}}
<x-mail::button :url="{{route('verify-email',$user->verification_token)}}">
Verify Your Account
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
````
    
- pass data to a view in mailer class
````php
protected Model $Data;

    public function __construct($passedData)
    {
        $this->Data = $passedData;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Email Subject',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            'welcome', null, null, null, ['data' => $this->Data]
        );

    }
````

- sort collection 
````php
    protected function sortData(Collection &$collection)
    {
        if (request()->has('sort_by')) {
            $attribute = request()->sort_by;
            $isDesc = request()->has('desc');
            $collection = $collection->sortBy($attribute, null, $isDesc);
        }
        return $collection;
    }
````

- filter collection something like `{{URL}}/users?verified=0`
````php
 protected function filterData(Collection &$collection)
    {
        $allowedAtt = User::getAttributesArray((new User())->find(1));
        foreach (request()->query() as $att => $val) {
            if (key_exists($att, $allowedAtt) && isset($val)) {
                $collection = $collection->where($att, $val)->values();
            }
        }
        return $collection;
    }
````


- convert a collection into a paginate
````php
 protected function paginate(Collection &$collection)
    {
        $page = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 15;
        $result = $collection->slice(($page - 1) * $perPage, $perPage)->values();
        $paginated = new LengthAwareP\aginator($result, $collection->count(), $perPage, $page, [
            'path' =>url()->current()
        ]);
        //$paginated->appends(request()->all());
        $collection = $paginated;
        return $paginated;

    }
````

- you can also allow user to custom `per_page` number with some restrictions
````php
protected function paginate(Collection &$collection)
    {
        $rules = [
            'per_page' => 'integer|min:2|max:50'
        ];
        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails())
            return $this->errorResponse($validator->getMessageBag(), 409);

        $page = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 15;
        if (request()->has('per_page'))
            $perPage = request()->per_page;
        $result = $collection->slice(($page - 1) * $perPage, $perPage)->values();
        $paginated = new LengthAwarePaginator($result, $collection->count(), $perPage, $page, [
            'path' => url()->current()
        ]);
        //$paginated->appends(request()->all());
        $collection = $paginated;
        return $paginated;

    }
````

- cache response with key equal to URL (with query parameters irrespective to there order as they will be sorted)
````php
    protected function cacheResponse(Collection $collection)
    {
        $url = request()->url();
        $queryParams = request()->query();
        ksort($queryParams);
        $queryString = http_build_query($queryParams);
        $fullUrl = "{$url}?{$queryString}";

        return Cache::remember($fullUrl, 30, function () use ($collection) {
            return $collection;
        });
    }
````
