<?php

  
namespace App\Jobs;
  
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Notifications\SendPassword;
  

class SendEmailJob implements ShouldQueue
{
   use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
  
    protected $user;
    protected $data = 'hello';
  
    /**
     * Create a new job instance.
     */
    public function __construct($user , $data)
    {
        $this->user = $user;
        $this->data = $data;
    }
  
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // $email = new SendEmailTest();
        $this -> user -> notify(new SendPassword($this -> data));
        // Mail::to($this->details['email'])->send($email);
    }
}
