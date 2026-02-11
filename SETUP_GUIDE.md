# URL Shortener Application Setup

## Completed Steps

✅ **1. Database Migrations**
- Created `urls` table migration with:
  - `user_id` (foreign key to users)
  - `original_url` (text)
  - `short_code` (unique, 10 chars)
  - `clicks` (default 0)
  
- Updated `users` table migration with:
  - `role` column (default: 'user')

✅ **2. Models**
- Created `Url` model with relationships and helper methods
- Updated `User` model with `urls()` relationship and `isAdmin()` method

✅ **3. Controllers**
- Created `UrlController` with methods:
  - `index()` - Display user's URLs
  - `store()` - Create new short URL
  - `show()` - Redirect to original URL and track clicks
  - `destroy()` - Delete URL
  - `admin()` - Admin dashboard with all URLs

✅ **4. Routes**
- Home page (/) - URL shortening form
- Dashboard (/dashboard) - User's URLs with stats
- Admin Dashboard (/admin/dashboard) - All URLs overview
- Short URL redirect (/{shortCode})

✅ **5. Views**
- **welcome.blade.php** - Beautiful home page with URL shortening form
- **dashboard.blade.php** - User dashboard with stats and URL management
- **admin/dashboard.blade.php** - Admin dashboard with system overview

✅ **6. Security**
- Created `AdminMiddleware` for protecting admin routes
- Created `UrlPolicy` for authorization
- Registered middleware in bootstrap/app.php

---

## Next Steps

### 1. Run Fresh Migrations

Since you've already run migrations, you need to refresh them to include the new columns:

```bash
php artisan migrate:fresh
```

**⚠️ Warning**: This will delete all existing data. If you have important data, use migrations rollback and re-run instead.

### 2. Create an Admin User

After running migrations, create a user and manually set them as admin:

```bash
php artisan tinker
```

Then in the tinker console:

```php
$user = App\Models\User::factory()->create([
    'name' => 'Admin User',
    'email' => 'admin@example.com',
    'password' => bcrypt('password'),
    'role' => 'admin'
]);
```

Or register a normal user through the web interface and then update via tinker:

```php
$user = App\Models\User::where('email', 'your@email.com')->first();
$user->role = 'admin';
$user->save();
```

### 3. Start the Development Server

```bash
npm run dev
```

In another terminal:

```bash
php artisan serve
```

### 4. Test the Application

1. **Home Page** (http://localhost:8000)
   - Try creating a short URL as a guest (will redirect to login)
   - Register/Login and create short URLs
   - Copy and test short URLs

2. **User Dashboard** (http://localhost:8000/dashboard)
   - View your shortened URLs
   - See click statistics
   - Create new URLs
   - Delete URLs

3. **Admin Dashboard** (http://localhost:8000/admin/dashboard)
   - Login with admin account
   - View all users' URLs
   - See system-wide statistics
   - Delete any URL

### 5. Optional Enhancements

Consider adding these features later:

- **QR Code Generation** for each short URL
- **Custom Short Codes** (let users choose their own)
- **Link Expiration** (set expiry dates for URLs)
- **Analytics** (track devices, locations, referrers)
- **API** for programmatic access
- **Rate Limiting** to prevent abuse

---

## File Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   └── UrlController.php
│   ├── Middleware/
│   │   └── AdminMiddleware.php
├── Models/
│   ├── Url.php
│   └── User.php
├── Policies/
│   └── UrlPolicy.php

database/
└── migrations/
    ├── 0001_01_01_000000_create_users_table.php (updated)
    └── 2026_02_02_173341_create_urls_table.php (updated)

resources/
└── views/
    ├── welcome.blade.php (home page)
    ├── dashboard.blade.php (user dashboard)
    └── admin/
        └── dashboard.blade.php (admin dashboard)

routes/
└── web.php (updated)

bootstrap/
└── app.php (updated)
```

---

## Features Implemented

✨ **User Features:**
- Shorten URLs with automatic short code generation
- View personal URL dashboard with statistics
- Track clicks on each URL
- Copy short URLs to clipboard
- Delete own URLs
- Responsive design with Tailwind CSS
- Dark mode support

✨ **Admin Features:**
- View all URLs from all users
- System-wide statistics (total URLs, clicks, users)
- Delete any URL
- User information display
- Color-coded click statistics

✨ **Security:**
- User authentication required for creating URLs
- Admin middleware for protected routes
- Authorization policy for URL deletion
- CSRF protection on all forms

---

## Troubleshooting

**Issue: "Column not found: role"**
- Run `php artisan migrate:fresh` to recreate tables with new columns

**Issue: "403 Forbidden" on admin dashboard**
- Ensure your user has `role = 'admin'` in the database

**Issue: Short URL not redirecting**
- Check that the short code exists in the database
- Ensure the URL has proper protocol (http:// or https://)

**Issue: Styles not loading**
- Run `npm install` and then `npm run dev`
- Check that Vite is running

---

## Database Schema

### users table
- id
- name
- email (unique)
- email_verified_at
- password
- **role** (user|admin) - NEW
- remember_token
- timestamps

### urls table
- id
- **user_id** (foreign key)
- **original_url** (text)
- **short_code** (string, unique)
- **clicks** (integer, default: 0)
- timestamps

---

All set! Your URL Shortener application is ready to use. Run the migrations and start the server to begin testing! 🚀
