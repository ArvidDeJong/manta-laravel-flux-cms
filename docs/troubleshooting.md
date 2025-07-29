# Troubleshooting

This page helps you solve common problems with the Manta Laravel Flux CMS package.

## Installation Problems

### ServiceProvider not found

**Problem:** `Class 'Manta\FluxCMS\FluxCMSServiceProvider' not found`

**Solution:**
1. Check if the package is correctly installed:
   ```bash
   composer show manta/laravel-manta-cms
   ```

2. Run composer dump-autoload:
   ```bash
   composer dump-autoload
   ```

3. Check if the ServiceProvider is correctly registered in `config/app.php`

### Migration Errors

**Problem:** `Table 'manta_routes' already exists`

**Solution:**
1. Check which migrations have already been executed:
   ```bash
   php artisan migrate:status
   ```

2. Rollback the migration if necessary:
   ```bash
   php artisan migrate:rollback --step=1
   ```

3. Run the migration again:
   ```bash
   php artisan migrate
   ```

## Command Problems

### Command not found

**Problem:** `Command "manta:sync-routes" is not defined`

**Solution:**
1. Check if the ServiceProvider is correctly registered
2. Clear the application cache:
   ```bash
   php artisan cache:clear
   php artisan config:clear
   ```

3. Check if the command is registered:
   ```bash
   php artisan list manta
   ```

### Sync Routes Command Errors

**Problem:** `Call to undefined method Route::getRoutes()`

**Solution:**
This can happen if you're using an older version of Laravel. Make sure you're using Laravel 12.x.

**Problem:** `SQLSTATE[42S02]: Base table or directory name 'manta_routes' doesn't exist`

**Solution:**
Run the migration first:
```bash
php artisan migrate
```

## Livewire Problems

### Component not found

**Problem:** `Unable to find component: [user-table]`

**Solution:**
1. Check if the component exists in `app/Livewire/`
2. Check the namespace in your component:
   ```php
   namespace App\Livewire; // Correct for Livewire 3
   ```

3. Clear the view cache:
   ```bash
   php artisan view:clear
   ```

### Livewire Assets not loaded

**Problem:** Livewire functionality doesn't work

**Solution:**
1. Make sure Livewire assets are published:
   ```bash
   php artisan livewire:publish --assets
   ```

2. Check if `@livewireStyles` and `@livewireScripts` are in your layout:
   ```blade
   <head>
       @livewireStyles
   </head>
   <body>
       @livewireScripts
   </body>
   ```

## FluxUI Problems

### FluxUI Components not found

**Problem:** `Component [flux:button] not found`

**Solution:**
1. Check if FluxUI is correctly installed:
   ```bash
   composer show livewire/flux
   ```

2. Publish FluxUI assets:
   ```bash
   php artisan flux:install
   ```

### Styling Problems

**Problem:** FluxUI components have no styling

**Solution:**
1. Check your `tailwind.config.js`:
   ```javascript
   content: [
       "./vendor/livewire/flux-pro/stubs/**/*.blade.php",
       "./vendor/livewire/flux/stubs/**/*.blade.php",
   ]
   ```

2. Rebuild your assets:
   ```bash
   npm run build
   ```

## Database Problems

### Connection Refused

**Problem:** `SQLSTATE[HY000] [2002] Connection refused`

**Solution:**
1. Check your database configuration in `.env`
2. Make sure your database server is running
3. Test the connection:
   ```bash
   php artisan tinker
   DB::connection()->getPdo();
   ```

### Permission Denied

**Problem:** Database permission errors

**Solution:**
1. Check database user permissions
2. Make sure the database user has CREATE, ALTER, DROP rights

## Performance Problems

### Slow Queries

**Solution:**
1. Enable query logging to identify slow queries:
   ```php
   DB::enableQueryLog();
   // Your code here
   dd(DB::getQueryLog());
   ```

2. Add database indexes where needed
3. Use eager loading for relationships:
   ```php
   $users = User::with('roles')->get();
   ```

### Memory Limit

**Problem:** `Fatal error: Allowed memory size exhausted`

**Solution:**
1. Increase memory limit in `php.ini`:
   ```ini
   memory_limit = 512M
   ```

2. Use chunking for large datasets:
   ```php
   User::chunk(100, function ($users) {
       foreach ($users as $user) {
           // Process user
       }
   });
   ```

## Cache Problems

### Stale Cache

**Problem:** Changes are not shown

**Solution:**
Clear all caches:
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## Logging & Debugging

### Debug Mode

Enable debug mode in `.env` for more detailed error messages:
```env
APP_DEBUG=true
APP_LOG_LEVEL=debug
```

### Log Files

Check the log files for more information:
```bash
tail -f storage/logs/laravel.log
```

### Query Debugging

Debug database queries:
```php
DB::listen(function ($query) {
    Log::info($query->sql, $query->bindings);
});
```

## Getting Help

If you continue to experience problems:

1. Check the [Laravel documentation](https://laravel.com/docs)
2. Check the [Livewire documentation](https://livewire.laravel.com/docs)
3. Check the [FluxUI documentation](https://fluxui.dev)
4. Create an issue in the package repository
5. Check existing issues for similar problems
