<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class QueryBookedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;

    /**
     * Create a new message instance.
     *
     * @param $appointment
     */
    public function __construct($appointment)
    {
        $this->appointment = $appointment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $appointment=$this->appointment;
        if($appointment->appointment_date){
            return $this->subject('Query Booked Successfully')
            ->view('emails.query_booked')
            ->with('appointment', $this->appointment);
        }
        else{
            return $this->subject('Query Booked Request Received Successfully')
            ->view('emails.query_booked')
            ->with('appointment', $this->appointment);
        }

        
    }
}
