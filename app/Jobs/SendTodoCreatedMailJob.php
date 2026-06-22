<?php

namespace App\Jobs;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendTodoCreatedMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Todo $todo;
    protected User $user;
    /**
     * Create a new job instance.
     */
    public function __construct(Todo $todo, User $user)
    {
        $this->todo = $todo;
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Simuler un envoi d'email (on va utiliser le driver 'log' pour le test)
        Log::info("Email envoyé à {$this->user->email} pour la todo : {$this->todo->title}");

        // Plus tard, on pourra utiliser Mail::to($this->user->email)->send(new TodoCreatedMail($this->todo));
    }
}
