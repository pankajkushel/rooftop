<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Appointment;
use App\Models\Franchise;
use App\Models\Order;
use App\Models\Quotation;
use App\Models\QuotationSection;
  
class InvoiceGeneratedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $quotation_id;
    //public $order_data;
 //echo $quotation_id;die; 
    public function __construct($data,$quotation_id)
    {
        $this->data = $data; 
		$this->quotation_id=$quotation_id;
        //echo $this->quotation_id;die;
    }

    public function build(){
    
	    $order_data =Quotation::with('appointment', 'franchise', 'quotaitonItem','quotaiton_section')->find($this->quotation_id);
        $pdf=PDF::loadView('download_quote',['order_data' => $order_data]);
        $number = rand(100,100000);  
        return $this->view('emails.invoice_generated')
                    ->subject('Invoice Generated Successfully')
					->attachData($pdf->output(),$number."-"."invoice.pdf")
                    ->with('data', $this->data);
    }
}
