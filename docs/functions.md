# When to Use Arrow Functions (`fn()`) in Laravel/PHP

Arrow functions (`fn()`) are best used in specific situations where they offer cleaner syntax without sacrificing readability:

### Best Use Cases for Arrow Functions:

1. **Single expression returns**: 
   When your function only needs to return one expression without any additional logic.

   ```php
   Route::get('/test', fn() => ['name' => 'John', 'age' => 30]);
   ```

2. **Inline callbacks**:
   For short callbacks that fit on a single line.

   ```php
   $names = $users->map(fn($user) => $user->name);
   ```

3. **Simple route handlers**:
   When you don't need a full controller method for a simple response.

   ```php
   Route::get('/user', fn(Request $request) => $request->user());
   ```

### When NOT to Use Arrow Functions:

1. **Complex logic**: 
   If you need multiple lines of code or conditional statements, use traditional functions.

2. **When readability suffers**:
   If the arrow function makes your code harder to understand.

3. **When you need to modify variables** (since arrow functions implicitly capture by value).

### Benefits of Arrow Functions:

1. **Conciseness**: Less boilerplate code
2. **Readability**: For simple operations
3. **Implicit return**: No need for the `return` keyword
4. **Variable capture**: Automatically captures parent scope variables

Arrow functions are simply a more concise syntax for simple, single-statement functions. Choose them when their brevity improves code readability.
