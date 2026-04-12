# Tools

## SSH
	sudo apt update
	sudo apt install net-tools nano ssh-server
	sudo systemctl status ssh
	sudo systemctl start ssh
	sudo systemctl enable ssh
	sudo ufw allow ssh
	sudo ufw enable

## DOCKER
	sudo apt update
	sudo apt install -y ca-certificates curl gnupg
	sudo install -m 0755 -d /etc/apt/keyrings
	curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg
	echo "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu $(. /etc/os-release && echo $VERSION_CODENAME) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null
	sudo apt update
	sudo apt install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin
	sudo usermod -aG docker $USER
	newgrp docker
	dpkg -l|grep docker


## INSTALLATION Docker / Docker Compose / Lavarel

	docker compose up -d --build
	docker restart portainer
	docker exec -it laravel_app bash
	cd /var/www
	composer create-project laravel/laravel
	

## Lavavel Log check
	docker exec -it laravel_app bash
	tail -f storage/logs/laravel.log



### CLEAN reinstall Stack
- rm -rf ./backend
- docker compose down -v


### Config
- docker exec -it laravel_app bash
- cd /var/www
- composer require laravel/sanctum
- php artisan vendor:publish --tag=sanctum-config
- php artisan migrate


## nano .env
```sh
APP_URL=http://192.168.0.102:8000
SESSION_DRIVER=file
SESSION_DOMAIN=192.168.0.102
SANCTUM_STATEFUL_DOMAINS=192.168.0.102:5173,192.168.0.105
```

## cors
- php artisan config:publish cors

## nano config/cors.php
```php
	'supports_credentials' => true,
	'paths' => ['api/*', 'sanctum/csrf-cookie'],
```

- php artisan optimize:clear

## nano bootstrap/app.php
```php
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

->withMiddleware(function (Middleware $middleware): void {
    $middleware->api(prepend: [
        EnsureFrontendRequestsAreStateful::class,
    ]);

    $middleware->redirectGuestsTo(fn () => null);
})
```

	php artisan key:generate
	php artisan optimize:clear

## nano database/migration/users.php
```php
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
			
		// 🔹 Basic Info
			$table->string('first_name', 100);
			$table->string('last_name', 100);
	
            //$table->string('name');
            //$table->string('email')->unique();
            //$table->timestamp('email_verified_at')->nullable();
            //$table->string('password');			
			
			$table->string('email', 150)->unique()->nullable();
			$table->string('password')->nullable();
			$table->string('phone', 20)->nullable();			

		// 🔹 Roles & Status
			$table->enum('role', ['admin', 'operator', 'customer', 'owner']);
			$table->enum('status', ['active', 'pending', 'suspended', 'blocked'])->default('pending');

		// 🔹 Verification
			$table->timestamp('email_verified_at')->nullable();
			$table->timestamp('phone_verified_at')->nullable();

		// 🔹 KYC / Driver Approval
			$table->enum('kyc_status', ['pending', 'approved', 'rejected'])->default('pending');
			$table->enum('driver_status', ['pending', 'approved', 'rejected'])->default('pending');

		// 🔹 Identity
			$table->enum('id_type', ['driver_license', 'passport'])->nullable();
			$table->string('id_number', 100)->nullable();
			$table->string('id_image')->nullable();

		// 🔹 UX / Tracking
			$table->boolean('profile_completed')->default(false);
			$table->timestamp('last_login_at')->nullable();

			
		// 🔐 Laravel Auth (KEEP THESE)			
            $table->rememberToken();
            $table->timestamps();
			$table->softDeletes();
			
        });
```

## nano app/Models/User.php
```php
protected $fillable = [
    'first_name',
    'last_name',
    'email',
    'password',
    'phone',
    'role',
    'status',
    'email_verified_at',
    'phone_verified_at',
    'kyc_status',
    'driver_status',
    'id_type',
    'id_number',
    'id_image',
    'profile_completed',
    'last_login_at',
];
```

## Drops ALL tables & Recreates them from migrations-code
	php artisan key:generate
	php artisan migrate:fresh



## List columns insert user
	php artisan tinker
```php
Schema::getColumnListing('users');
//-------------------------------------
App\Models\User::create([
    'first_name' => 'Admin',
    'last_name' => 'User',
    'email' => 'admin@test.com',
    'password' => bcrypt('password123'),

    'phone' => null,

    'role' => 'admin',
    'status' => 'active',

    'email_verified_at' => now(),
    'phone_verified_at' => null,

    'kyc_status' => 'approved',
    'driver_status' => 'approved',

    'id_type' => null,
    'id_number' => null,
    'id_image' => null,

    'profile_completed' => true,
    'last_login_at' => null,
]);
```

# API - Setup
- Create Controller <mark>app/Http/Controllers/AuthController.php
- Define Route <mark>routes/app.php</mark>
- Register it in Laravel <mark>bootstrap/app.php
- Clear cache
- test


	## *Create Controller*
		$php artisan make:controller AuthController
		$sudo nano app/Http/Controllers/AuthController.php

	```php
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Hash;
	use App\Models\User;

	class AuthController extends Controller
	{
		// 🔐 LOGIN
		public function login(Request $request)
		{
			$request->validate([
				'email' => 'required|email',
				'password' => 'required'
			]);

			if (!auth()->attempt($request->only('email', 'password'))) {
				return response()->json([
					'message' => 'Invalid credentials'
				], 401);
			}

			return response()->json(auth()->user());
		}

		// 👤 CURRENT USER
		public function me(Request $request)
		{
			return response()->json($request->user());
		}

		// 🚪 LOGOUT
		public function logout(Request $request)
		{
			auth()->logout();

			return response()->json([
				'message' => 'Logged out'
			]);
		}
	}
	```


	## *Define Route*
	- $touch routes/api.php
	- $sudo nano routes/api.php

	```php
		use Illuminate\Support\Facades\Route;
		use App\Http\Controllers\AuthController;

		// Public
		Route::post('/auth/login', [AuthController::class, 'login']);

		// Protected
		Route::middleware('auth:sanctum')->group(function () {
			Route::get('/auth/me', [AuthController::class, 'me']);
			Route::post('/auth/logout', [AuthController::class, 'logout']);
		});
	```

	## *Register it in Laravel*
	- $sudo nano bootstrap/app.php

	```php
	->withRouting(
		web: __DIR__.'/../routes/web.php',
		api: __DIR__.'/../routes/api.php',   // ✅ ADD THIS
		commands: __DIR__.'/../routes/console.php',
		health: '/up',
	)
	-----------------------------------------------------------
	use Illuminate\Session\Middleware\StartSession;
	use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
	use Illuminate\Cookie\Middleware\EncryptCookies;
	use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

	->withMiddleware(function (Middleware $middleware): void {

		// Sanctum stateful support
		$middleware->api(prepend: [
			EnsureFrontendRequestsAreStateful::class,
		]);

		// 🔥 THIS ENABLES SESSION (REQUIRED)
		$middleware->appendToGroup('api', [
			EncryptCookies::class,
			AddQueuedCookiesToResponse::class,
			StartSession::class,
		]);

		// Prevent redirect to login route
		$middleware->redirectGuestsTo(fn () => null);
	})
	```	

## *Clear cache*
	php artisan optimize:clear
	php artisan route:list

<br>

# TEST
```sh
curl -i -c cookies.txt http://192.168.0.102:8000/sanctum/csrf-cookie

curl -i -b cookies.txt -c cookies.txt \
-X POST http://192.168.0.102:8000/api/auth/login \
-H "Content-Type: application/json" \
-H "Accept: application/json" \
-d '{"email":"admin@test.com","password":"password123"}'


curl -i -b cookies.txt http://192.168.0.102:8000/api/auth/me -H "Accept: application/json"
```

<br>

# React

```sh
npm create vite@latest myapp
cd myapp
npm install
npm run dev
npm install axios
npm run dev -- --host
```








