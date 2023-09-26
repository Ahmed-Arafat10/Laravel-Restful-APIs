````php
App\Models\Task::factory()->times(1000)->create(); 
````

````php
public function definition(): array
    {
        return [
            'user_id' => User::all()->random()->id,
            'name' => $this->faker->unique()->sentence(),
            'description' => $this->faker->text(),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high']),
        ];
    }
````


````php
php artiasn make:resource TaskResource
````

- TaskResource.php 
````php
public function toArray(Request $request): array
    {
        return [
          'id' => (string) $this->id,
            'attributes' => [
                'name' => $this->name,
                'description' => $this->description,
                'priority' => $this->priority,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
                'user_id' => $this->user_id
            ],
            'relationships' =>[
                'user_id' => (string) $this->user->id ,
                'userName' => $this->user->name ,
                'email' => $this->user->email,
            ]
        ];
    }
````


````php
 $data = TaskResource::collection(
            Task::where('user_id', Auth::id())->get()
        );
````

````php
$data = new TaskResource($task);
````


````php
php artisan schedule:list
````

- At `app` > `console` > `kernal.php`
````php
 protected function schedule(Schedule $schedule): void
    {
        $schedule->command('sanctum:prune-expired --hours=24')->daily();
    }
````
