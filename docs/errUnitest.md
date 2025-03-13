# Debugging Process for 404 vs 201 Status Code Issue in Laravel Test

When your test is expecting a 201 response but gets a 404, it means the endpoint couldn't be found. Let's go through a systematic debugging process:

## 1. Check Route Registration

First, verify that your route is correctly registered:

```bash
php artisan route:list --path=cvs
```

This will show all routes containing "cvs" in their path. Ensure you see:
- POST /api/cvs

## 2. Examine Route File

Check your route file (likely api.php):

```php
// routes/api.php
Route::post('/cvs', [\App\Http\Controllers\Api\CVController::class, 'store']);
```

Make sure:
- The route path is correct
- The controller namespace is correct
- The method name is correct

## 3. Check Controller Method

Verify your `CVController@store` method exists and has correct parameters:

```php
// app/Http/Controllers/Api/CVController.php
public function store(Request $request)
{
    // Implementation
}
```

## 4. Verify Test Request Path

In your test:

```php
$response = $this->postJson('/api/cvs', [
    'title' => 'Test CV',
    'cv_file' => $file,
]);
```

Make sure this path matches exactly what's in your route file.

## 5. Debug with Request Logging

Add temporary logging to track the request:

```php
// In a service provider's boot method
\Illuminate\Support\Facades\Route::matched(function ($event) {
    \Illuminate\Support\Facades\Log::info('Route matched', [
        'uri' => $event->request->path(),
        'method' => $event->request->method(),
    ]);
});
```

## 6. Inspect Headers and Response Body

Modify your test to dump more information:

```php
$response = $this->postJson('/api/cvs', [
    'title' => 'Test CV',
    'cv_file' => $file,
]);

// For debugging
echo "Response Status: " . $response->getStatusCode() . "\n";
echo "Response Body: " . $response->getContent() . "\n";
```

## 7. Check Route Middleware

Ensure middleware isn't preventing the route from being accessed:

```php
// In your test
public function testStoreCv()
{
    $user = User::factory()->create();
    Sanctum::actingAs($user, ['*']); // Add all abilities to be safe
    
    // Rest of the test...
}
```

## 8. Check for API Prefix Issues

If you're using an API prefix in `RouteServiceProvider.php`, ensure your test path includes it:

```php
// If your API routes have a prefix like 'api/v1'
$response = $this->postJson('/api/v1/cvs', [
    'title' => 'Test CV',
    'cv_file' => $file,
]);
```

## 9. Inspect Sanctum Authentication

Make sure Sanctum is working correctly:

```php
// At the beginning of your test
$user = User::factory()->create();
Sanctum::actingAs($user);

// Verify authentication is working
$this->assertTrue(auth()->check());
$this->assertEquals($user->id, auth()->id());
```

By methodically going through these steps, you should be able to identify why your route is returning a 404 instead of a 201 status code.
