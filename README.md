
# Laravel Starter Kit by Codeloops

> A rapid starter kit for Laravel 12+ that automates the installation and setup of Nova, Spatie MediaLibrary, Nova TinyMCE Editor, Spatie Permission, and scaffolds models, seeders, migrations, Nova resources, policies, and now **Blog** features.


## Features

- One-command install for Nova, MediaLibrary, Nova TinyMCE Editor, Spatie Permission
- Interactive wizard to scaffold **Page** and **Blog** features (models, Nova resources, policies, migrations)
- Publishes all stubs, migrations, and config files automatically
- Runs database migrations and seeds permissions

## Installation


### Prerequisites

- Laravel 12+
- Composer
- Nova license (if using Nova)




### Required Installation Order

**You must follow these steps in the exact order below for the starter kit to work properly:**

#### 1. Install the Package

```bash
composer require codeloops/starter-kit:dev-master
```

> **Note:** If you have SSH configured, you can use `git@github.com:codel00ps/starter-kit.git`.

#### 2. Run Installation Commands

**Interactive Wizard (Recommended)**
```bash
php artisan starter:wizard
```
The wizard will install core components and permissions, then let you select **Page** and **Blog** features interactively.

#### 3. Configure User Model
Edit `app/Models/User.php` to include the HasRoles trait:
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
  use HasFactory, Notifiable, HasRoles;

  // ... rest of your User model ...
}
```

#### 4. Configure DatabaseSeeder
Edit `database/seeders/DatabaseSeeder.php` to include the permissions seeder:
```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\PermissionsSeeder;

class DatabaseSeeder extends Seeder
{
  public function run(): void
  {
    // ... other seeders ...

    $this->call([
      PermissionsSeeder::class,
    ]);
  }
}
```

#### 5. Configure PermissionsSeeder (if you installed Page or Blog features)
> ⚠️ **IMPORTANT:** If you installed **Page** or **Blog** features, update your `PermissionsSeeder.php`:

```php
$collection = collect([
  'Users',
  'Roles',
  'Permissions',
  'Pages',      // Add if you installed Page features
  'Posts',      // Add if you installed Blog features
  'Categories', // Add if you installed Blog features
]);
```

**If you did NOT install Page or Blog features, skip this step.**

#### 6. Run Database Operations
```bash
php artisan migrate
php artisan db:seed
```


## Manual Installation (Alternative to Wizard)

If you prefer to run commands individually:

**Install Core Components**
```bash
php artisan starter:core
```

**Install Permission System**
```bash
php artisan starter:permissions
```

**Install Page Features (Optional)**
```bash
php artisan starter:page
```

**Install Blog Features (Optional)**
```bash
php artisan starter:blog
```

If you run `starter:page` or `starter:blog`, remember to update PermissionsSeeder as shown above.


## Available Commands

- `php artisan starter:wizard` — Interactive wizard to select and install features (recommended)
- `php artisan starter:core` — Install core components (Nova, MediaLibrary, TinyMCE)
- `php artisan starter:permissions` — Install permission system
- `php artisan starter:page` — Install page features
- `php artisan starter:blog` — Install blog features


## Configuration Checklist

**Before running any installation commands:**
1. ✅ Install the package
2. ✅ Configure User model with HasRoles trait
3. ✅ Configure DatabaseSeeder to include PermissionsSeeder

**After running installation commands:**
1. ✅ Update PermissionsSeeder if you installed Page or Blog features
2. ✅ Run migrations: `php artisan migrate`
3. ✅ Run seeders: `php artisan db:seed`
4. ✅ Create Nova user: `php artisan nova:user`


## What's Included

After installation, you'll have:

- **Models:**
  - `app/Models/User.php` (permissions)
  - `app/Models/Permission.php` (permissions)
  - `app/Models/Role.php` (permissions)
  - `app/Models/Page.php` (page)
  - `app/Models/Post.php` (blog)
  - `app/Models/Category.php` (blog)

- **Nova Resources:**
  - `app/Nova/Permission.php` (permissions)
  - `app/Nova/Role.php` (permissions)
  - `app/Nova/Page.php` (page)
  - `app/Nova/Post.php` (blog)
  - `app/Nova/Category.php` (blog)

- **Policies:**
  - `app/Policies/UserPolicy.php` (permissions)
  - `app/Policies/RolePolicy.php` (permissions)
  - `app/Policies/PermissionPolicy.php` (permissions)
  - `app/Policies/PagePolicy.php` (page)
  - `app/Policies/PostPolicy.php` (blog)
  - `app/Policies/CategoryPolicy.php` (blog)

- **Seeders:**
  - `database/seeders/PermissionsSeeder.php` (permissions)

- **Migrations:**
  - Spatie Permission tables (permissions)
  - MediaLibrary tables
  - Additional permission columns (permissions)
  - `create_page_table.php` (page)
  - `create_posts_table.php` (blog)
  - `create_categories_table.php` (blog)


## Installation Complete!

Your Laravel starter kit is now ready! Access Nova at `/nova` and start building your application with a pre-configured permission system, page, and blog features.

---

**Originally created by @itskrayem · Maintained by @Codeloops**


