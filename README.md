# RSH Transparencia - Laravel 7

Aplicación para gestión de solicitudes de transparencia del Registro Social de Hogares. Desarrollada en Laravel 7 con generación de PDFs, envío de emails con jobs en cola, e integración con SSO gubernamental (Clave Única).

## 🎯 Características

- ✅ **Gestión de solicitudes de transparencia** con workflow completo
- ✅ **Generación de PDFs** con wkhtmltopdf
- ✅ **Envío de emails** con Laravel Queues (jobs asíncronos)
- ✅ **SSO Clave Única** (gobierno chileno)
- ✅ **Exportación a Excel** con Maatwebsite
- ✅ **Dashboard de seguimiento** de solicitudes
- ✅ **Gestión de usuarios y perfiles**
- ✅ **Log viewer** para debugging

## 🛠️ Stack Tecnológico

- **PHP**: 7.3
- **Laravel**: 7.24
- **Oracle**: yajra/laravel-oci8 ^7.0
- **PDF**: barryvdh/laravel-snappy + wkhtmltopdf
- **Excel**: maatwebsite/excel ^3.1
- **HTTP**: guzzlehttp/guzzle ^7.0
- **CORS**: fruitcake/laravel-cors ^2.0
- **Alerts**: realrashid/sweet-alert ^3.2
- **Build**: Laravel Mix 5 (Webpack)
- **CSS**: Sass + sass-loader
- **Testing**: PHPUnit ^8.5, Mockery ^1.3.1

## 🚀 Instalación

```bash
# Clonar repositorio
git clone <repo-url>
cd rsh-transparencia

# Instalar dependencias
composer install
npm install

# Configurar entorno
cp .env.example .env
php artisan key:generate

# Configurar Oracle en .env
DB_CONNECTION=oracle
DB_HOST=your-host
DB_PORT=1521
DB_SERVICE=your-service
DB_USERNAME=your-user
DB_PASSWORD=your-password

# Migraciones
php artisan migrate

# Compilar assets
npm run dev
```

## 📋 Módulos Principales

### 1. Gestión de Solicitudes

```php
// app/Http/Controllers/SolicitudController.php
class SolicitudController extends Controller {
    // Listar solicitudes
    public function index() {
        $solicitudes = Solicitud::with('usuario', 'estado')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('solicitudes.index', compact('solicitudes'));
    }
    
    // Crear solicitud
    public function store(Request $request) {
        $validated = $request->validate([
            'tipo' => 'required|in:acceso,rectificacion,eliminiacion',
            'descripcion' => 'required|string',
            'documentos' => 'required|array',
        ]);
        
        $solicitud = Solicitud::create([
            'usuario_id' => Auth::id(),
            'tipo' => $validated['tipo'],
            'descripcion' => $validated['descripcion'],
            'estado' => 'PENDIENTE',
        ]);
        
        // Generar PDF de confirmación
        $pdf = SnappyPdf::loadView('solicitudes.pdf.confirmacion', compact('solicitud'));
        $pdf->save(storage_path("app/solicitudes/{$solicitud->id}.pdf"));
        
        // Dispatch email job
        EnviarEmailSolicitud::dispatch($solicitud);
        
        return redirect()->route('solicitudes.show', $solicitud);
    }
}
```

### 2. Seguimiento

```php
// app/Http/Controllers/SeguimientoController.php
class SeguimientoController extends Controller {
    public function show($id) {
        $solicitud = Solicitud::with('historial', 'documentos')->findOrFail($id);
        
        return view('seguimiento.show', compact('solicitud'));
    }
}
```

### 3. Generación de PDFs

```php
use Barryvdh\Snappy\Facades\SnappyPdf;

// Generar PDF desde vista
$pdf = SnappyPdf::loadView('reportes.solicitud', [
    'solicitud' => $solicitud,
    'documentos' => $documentos,
]);

// Descargar
return $pdf->download("solicitud_{$solicitud->id}.pdf");

// Guardar en storage
$pdf->save(storage_path("app/solicitudes/{$solicitud->id}.pdf"));

// Configurar opciones
$pdf = SnappyPdf::setOption('page-size', 'Letter')
    ->setOption('margin-top', '10mm')
    ->loadView('reportes.solicitud', $data);
```

### 4. Jobs en Cola (Emails)

```php
// app/Jobs/SendEmailJob.php
class SendEmailJob implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    protected $solicitud;
    
    public function __construct(Solicitud $solicitud) {
        $this->solicitud = $solicitud;
    }
    
    public function handle() {
        $usuario = $this->solicitud->usuario;
        
        Mail::send('transparencia.email.solicitud', [
            'solicitud' => $this->solicitud,
        ], function ($message) use ($usuario) {
            $message->to($usuario->email)
                ->subject('Confirmación de Solicitud de Transparencia');
        });
    }
}

// Dispatch desde controlador
EnviarEmailSolicitud::dispatch($solicitud);
```

### 5. Exportación a Excel

```php
use Maatwebsite\Excel\Facades\Excel;

class SolicitudesExport implements FromQuery {
    public function query() {
        return Solicitud::query()
            ->where('estado', 'APROBADA')
            ->with('usuario');
    }
}

// Descargar Excel
return Excel::download(new SolicitudesExport, 'solicitudes.xlsx');
```

### 6. Autenticación SSO (Clave Única)

```php
// app/Http/Controllers/AuthController.php
class AuthController extends Controller {
    public function loginsso(Request $request) {
        $token = $request->input('token_clave_unica');
        
        // Validar token con API Clave Única
        $client = new Client();
        $response = $client->post(env('CLAVE_UNICA_VALIDATE_URL'), [
            'json' => ['token' => $token],
        ]);
        
        $data = json_decode($response->getBody(), true);
        
        if ($data['valid']) {
            $usuario = Usuario::where('run', $data['run'])->first();
            
            if ($usuario) {
                Auth::login($usuario);
                return redirect()->route('dashboard');
            }
        }
        
        return redirect()->route('login')->with('error', 'Token inválido');
    }
}
```

## 🔐 Autenticación

### Middleware Auth

```php
// routes/web.php
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', 'DashboardController@index');
    Route::resource('/solicitudes', 'SolicitudController');
    Route::get('/seguimiento/{id}', 'SeguimientoController@show');
});
```

### SSO Clave Única

```env
# .env
CLAVE_UNICA_CLIENT_ID=your-client-id
CLAVE_UNICA_CLIENT_SECRET=your-secret
CLAVE_UNICA_VALIDATE_URL=https://claveunica.gob.cl/api/validate
```

## 📊 Modelos

```php
class Solicitud extends Model {
    protected $fillable = [
        'usuario_id',
        'tipo',
        'descripcion',
        'estado',
        'fecha_respuesta',
    ];
    
    public function usuario() {
        return $this->belongsTo(Usuario::class);
    }
    
    public function historial() {
        return $this->hasMany(HistorialSolicitud::class);
    }
    
    public function documentos() {
        return $this->hasMany(DocumentoSolicitud::class);
    }
}

class Usuario extends Model {
    protected $table = 'usuarios';
    
    public function solicitudes() {
        return $this->hasMany(Solicitud::class);
    }
}
```

## 🎨 Frontend

### Laravel Mix (Webpack)

```javascript
// webpack.mix.js
const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css')
   .version();
```

### Scripts NPM

```bash
# Desarrollo
npm run dev

# Watch (hot reload)
npm run watch

# Producción
npm run production
```

## 🧪 Testing

```bash
# Ejecutar tests
vendor/bin/phpunit

# Feature tests
vendor/bin/phpunit tests/Feature

# Con cobertura
vendor/bin/phpunit --coverage-html coverage
```

## 🐛 Debugging

### Log Viewer

```
/desarrollo/logs/{token}
```

Acceso condicionado a variable de ambiente:

```php
if (env('AMBIENTE_DEBUG')) {
    Route::get('/desarrollo/logs/{token}', function ($token) {
        if ($token === env('LOG_VIEWER_TOKEN')) {
            return app()->make('rap2hpoutre/laravel-log-viewer');
        }
        abort(403);
    });
}
```

## 📦 Comandos Artisan

```bash
# Generar PDFs masivamente
php artisan transparencia:generar-pdf --all

# Enviar emails pendientes
php artisan transparencia:enviar-emails

# Limpiar solicitudes antiguas
php artisan transparencia:limpiar --days=90
```

## 🔧 Configuración

### CORS

```php
// config/cors.php
return [
    'paths' => ['api/*'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['*'],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];
```

### Email

```env
# .env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
```

## 📝 Notas de Desarrollo

### Problemas comunes

**wkhtmltopdf no encontrado:**
```bash
# Instalar binario
composer require h4cc/wkhtmltopdf-amd64:0.12.x

# Configurar ruta en config/snappy.php
'binary' => base_path('vendor/h4cc/wkhtmltopdf-amd64/bin/wkhtmltopdf-amd64'),
```

**Jobs no se procesan:**
```bash
# Ejecutar queue worker
php artisan queue:work

# O usar supervisor en producción
[program:transparencia-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/app/artisan queue:work
autostart=true
autorestart=true
numprocs=3
```

**Oracle connection timeout:**
```php
// config/database.php
'oracle' => [
    'driver' => 'oracle',
    'timeout' => 30,
    // ...
],
```

## 👤 Autor

**Ysaías Alfonso Pérez Campos**  
Senior Backend Engineer  
Email: ysaiascampos@gmail.com  
LinkedIn: https://www.linkedin.com/in/ysaias-perez-campos-50626116a/

## 📄 Licencia

Proyecto desarrollado para el Gobierno de Chile - Registro Social de Hogares.
