<?php

use App\Mail\SessionAutoClosedNotification;
use App\Models\WorkSession;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Mail;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        // ➜ Enregistrement des middlewares personnalisés
        $middleware->alias([
            'check.login.token' => \App\Http\Middleware\CheckLoginTokenMiddleware::class,
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->withSchedule(function (Schedule $schedule) {


        $schedule->call(function () {

            $heureCloture = env('POINTEUSE_HEURE_CLOTURE','22:00');
            $now = now()->timezone('Europe/Paris');


            $clotureTime = $now->copy()->setTimeFromTimeString($heureCloture);


            if ($now->greaterThanOrEqualTo($clotureTime) &&
                $now->diffInMinutes($clotureTime) <= 1) {


                $sessions = WorkSession::whereNull('WRK_Dte_Heure_Fin')->get();

                foreach ($sessions as $s) {
                    $s->WRK_Dte_Heure_Fin = $now->format('Y-m-d H:i:s');
                    $s->WRK_Duree_Minutes = Carbon::parse($s->WRK_Dte_Heure_Deb, 'Europe/Paris')->diffInMinutes($now);
                    $s->WRK_Est_Cloture_Auto = true;
                    $s->WRK_Type_Cloture = 'auto';
                    $s->save();
                }

                // Notifications email
                if ($sessions->count() > 0) {
                    $admins = env('POINTEUSE_EMAIL_ADMIN');

                    foreach ($admins as $mail) {
                        Mail::to(trim($mail))
                            ->send(new SessionAutoClosedNotification($sessions));
                    }
                }
            }

        })->everyMinute();

    })
    ->create();
