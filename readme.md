# Paymenter - Discord linked roles

## What is this?

This extension allows you to give your customers a role after they have paid for a product. This is done by linking their Discord account to their account on your website. This extension is made for the [Paymenter](https://paymenter.org).

## How to install

1. Download the latest version of the extension from the [releases page](/releases).
2. Upload the extension to your server.
3. Add this to the web.php file in the routes folder:
After the

```php
Route::post('/credits', [App\Http\Controllers\Clients\HomeController::class, 'addCredits'])->name('clients.credits.add')->middleware(['auth']);
```

add this:

```php
Route::get('/linkedroles', [App\Http\Controllers\LinkedRoleController::class, 'index'])->name('linkedroles.index')->middleware(['auth']);
Route::get('/linkedroles/callback', [App\Http\Controllers\LinkedRoleController::class, 'callback'])->name('linkedroles.callback')->middleware(['auth']);
```

4. Push roles to discord:
Run the following command in the terminal:

```bash
php artisan discord:link
```

Then follow the instructions in the terminal.
5. Done!

