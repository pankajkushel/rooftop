<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentSuccessMail extends Mailable
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
            return $this->subject('Appointment Schduled Successfully')
            ->view('emails.appointment_success')
            ->with('appointment', $this->appointment);
        }
        else{
            return $this->subject('Appointment Request Received Successfully')
            ->view('emails.appointment_success')
            ->with('appointment', $this->appointment);
        }

        
    }
}
