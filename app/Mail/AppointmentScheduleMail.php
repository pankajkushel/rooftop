<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentScheduleMail extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;

    /**
     * Create a new message instance.
     *
     * @param $appointment
     */
    public function __construct($appointment, $appointmentDate, $appointmentTime, $franchiseName)
    {
        $this->appointment = $appointment;
        $this->appointmentDate = $appointmentDate;
        $this->appointmentTime = $appointmentTime;
        $this->franchiseName = $franchiseName;
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
            ->view('emails.appointment_schedule')
            ->with([
                'appointment' => $this->appointment,
                'appointmentDate' => $this->appointmentDate,
                'appointmentTime' => $this->appointmentTime,
                'franchiseName' => $this->franchiseName,
            ]);
        }
        

        
    }
}
