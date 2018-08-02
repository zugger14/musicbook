<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications\SongPurchased;
use Paypalpayment;
use App\Order;
use App\Payment;
use Auth;
use App\Song;
use App\User;

class PaypalController extends Controller
{

    /*
        every payments that have been created aprroved or failed
        after collection all payment details make a call to this method to get all latest payment information ..change count to but same payment data appear maybe due to sandbox.
    */
	public function index()
    {
        $payments = Paypalpayment::getAll(['count' => 1, 'start_index' => 0], Paypalpayment::apiContext());   
        return response()->json([$payments->toArray()], 200);
    }

    /*
        gets the payment information after paypal payment success and stores in order table and calls show() method to show detail
    */
    public function store()
    {
        $payment_id = request()->get('paymentId');

        $order = Order::where('paypal_paymentid', $payment_id)->where('user_id', Auth::id())->first();//selects previousy submitted form for purchase of songs..
        $order->paypal_payerid = request()->get('PayerID'); 
        $order->payal_token = request()->get('token');//payal bhayecha db ma
        $order->paid_order = 1;
        $order->save();

        $payment = new Payment;
        $payment->song_id = $order->song_id;
        $payment->user_id = $order->user_id;
        $payment->order_id = $order->id;
        $payment->status = 'remaining';
        $payment->save();

        //$payment = $this->show($payment_id);
        //dd($payment);

        $song = Song::find($order->song_id);
        $user = User::find($song->user_id);
        $user->notify(new SongPurchased(Auth::user(), $song, $order));

        return redirect()->route('songs.download', $payment_id);
    }

    /*
        payment by particular payment id
    */
    public function show($payment_id)
    { 
        $payment = Paypalpayment::getById($payment_id, Paypalpayment::apiContext());
        return $payment->toArray();//if i dont toArray also blade template can parse it but cannot read when set as response()->json()
    }

    /*
        Process payment using credit card
    */
     public function paywithCreditCard(Request $r)
    {
        // ### Address
        // Base Address object used as shipping or billing
        // address in a payment. [Optional]
        /*$shippingAddress = Paypalpayment::shippingAddress();
        $shippingAddress->setLine1("3909 Witmer Roadss")
        ->setLine2("Niagara Falls")
        ->setCity("Niagara Falls")
        ->setState("NY")
        ->setPostalCode("14305")
        ->setCountryCode("US")
        ->setPhone("716-298-1822")
        ->setRecipientName("Jhonaaaaaaae");*/

        // ### CreditCard
        $card = Paypalpayment::creditCard();
        $card->setType("visa")
        ->setNumber("4758411877817150")
        ->setExpireMonth("05")
        ->setExpireYear("2019")
        ->setCvv2("456")
        ->setFirstName("Joesdas")
        ->setLastName("Shoppeaaaaaar");

        // ### FundingInstrument
        // A resource representing a Payer's funding instrument.
        // Use a Payer ID (A unique identifier of the payer generated
        // and provided by the facilitator. This is required when
        // creating or using a tokenized funding instrument)
        // and the `CreditCardDetails`
        $fi = Paypalpayment::fundingInstrument();
        $fi->setCreditCard($card);

        // ### Payer
        // A resource representing a Payer that funds a payment
        // Use the List of `FundingInstrument` and the Payment Method
        // as 'credit_card'
        $payer = Paypalpayment::payer();
        $payer->setPaymentMethod("credit_card")
        ->setFundingInstruments([$fi]);

        $item1 = Paypalpayment::item();
        $item1->setName('Ground Coffee 40 oz')
        ->setDescription('Ground Coffee 40 oz')
        ->setCurrency('USD')
        ->setQuantity(1)
        //->setTax(0.3)
        ->setPrice(7.50);

        $item2 = Paypalpayment::item();
        $item2->setName('Granola bars from card')
        ->setDescription('Granola Bars with Peanuts')
        ->setCurrency('USD')
        ->setQuantity(5)
        //->setTax(0.2)
        ->setPrice(2);


        $itemList = Paypalpayment::itemList();
        $itemList->setItems([$item1,$item2]);
        //->setShippingAddress($shippingAddress);


        $details = Paypalpayment::details();
        $details->setShipping("1.2")
        ->setTax("1.3")
                //total of items prices
        ->setSubtotal("17.5");

        //Payment Amount
        $amount = Paypalpayment::amount();
        $amount->setCurrency("USD")
        //the total is $17.8 = (16 + 0.6) * 1 ( of quantity) + 1.2 ( of Shipping).
        ->setTotal("20")
        ->setDetails($details);

        // ### Transaction
        // A transaction defines the contract of a
        // payment - what is the payment for and who
        // is fulfilling it. Transaction is created with
        // a `Payee` and `Amount` types

        $transaction = Paypalpayment::transaction();
        $transaction->setAmount($amount)
        ->setItemList($itemList)
        ->setDescription("Payment description")
        ->setInvoiceNumber(uniqid());

        // ### Payment
        // A Payment Resource; create one using
        // the above types and intent as 'sale'

        $payment = Paypalpayment::payment();

        $payment->setIntent("sale")
        ->setPayer($payer)
        ->setTransactions([$transaction]);

        try {
            // ### Create Payment
            // Create a payment by posting to the APIService
            // using a valid ApiContext
            // The return object contains the status;
            $payment->create(Paypalpayment::apiContext());
        } catch (\PayPalConnectionException $ex) {
            return response()->json(["error" => $ex->getMessage()], 400);
        }

        //check state then go to next download
        /*if($payment->state === "approved") {
        return view('songs.download')->withPayment($payment);*/
        //store current user in order table for giving them download acces for songs later on also..
        //then route to download method
        //}
        return response()->json([$payment->toArray()], 200);
    }

    public function paywithPaypal(Request $r)
    {   
        $song = Song::where('id', $r->id)->first();
        if($song->amount != $r->amount) {
            return response()->json('sorry the amount for the song is not correct.please start the process again from begining', 200);
        }

        // ### Address
        // Base Address object used as shipping or billing
        // address in a payment. [Optional]

        /*$shippingAddress= Paypalpayment::shippingAddress();
        $shippingAddress->setLine1("3909 Witmer Road")
        ->setLine2("Niagara Falls")
        ->setCity("Niagara Falls")
        ->setState("NY")
        ->setPostalCode("14305")
        ->setCountryCode("US")
        ->setPhone("716-298-1822")
        ->setRecipientName("Jhone");
        */
        // ### Payer
        // A resource representing a Payer that funds a payment
        // Use the List of `FundingInstrument` and the Payment Method
        // as 'credit_card'
        $payer = Paypalpayment::payer();
        $payer->setPaymentMethod("paypal");

        $item1 = Paypalpayment::item();
        $item1->setName($r->title)
        ->setDescription('about description')
        ->setCurrency('USD')
        ->setQuantity(1)
        //->setTax(0.3)
        ->setPrice($r->amount);
        /*
        $item2 = Paypalpayment::item();
        $item2->setName('Granola bars from paypal')
        ->setDescription('Granola Bars with Peanuts')
        ->setCurrency('USD')
        ->setQuantity(5)
        ->setTax(0.2)
        ->setPrice(2);
        */
        $itemList = Paypalpayment::itemList();
        $itemList->setItems([$item1]);
        // ->setShippingAddress($shippingAddress);

        /*$details = Paypalpayment::details();
        $details->setShipping("1.2")
        ->setTax("1.3")
        //total of items prices
        ->setSubtotal("17.5");
        */
        
        //Payment Amount
        $amount = Paypalpayment::amount();
        $amount->setCurrency("USD")
        //the total is $17.8 = (16 + 0.6) * 1 ( of quantity) + 1.2 ( of Shipping).
        ->setTotal($r->amount);
        //->setDetails($details);

        // ### Transaction
        // A transaction defines the contract of a
        // payment - what is the payment for and who
        // is fulfilling it. Transaction is created with
        // a `Payee` and `Amount` types

        $transaction = Paypalpayment::transaction();
        $transaction->setAmount($amount)
        ->setItemList($itemList)
        ->setDescription("Payment description")
        ->setInvoiceNumber(uniqid());

        // ### Payment
        // A Payment Resource; create one using
        // the above types and intent as 'sale'

        $redirectUrls = Paypalpayment::redirectUrls();
        $redirectUrls->setReturnUrl(url("/payments/success"))
        ->setCancelUrl(url("/payments/fails"));

        $payment = Paypalpayment::payment();

        $payment->setIntent("sale")
        ->setPayer($payer)
        ->setRedirectUrls($redirectUrls)
        ->setTransactions([$transaction]);

        try {
            // ### Create Payment
            // Create a payment by posting to the APIService
            // using a valid ApiContext
            // The return object contains the status;
            $payment->create(Paypalpayment::apiContext());
        } catch (\PayPalConnectionException $ex) {
            return response()->json(["error" => $ex->getMessage()], 400);
        }
        
        //after payment success
        $payment_id = $payment->id;
        $order = Order::orderBy('id', 'DESC')->where('user_id', Auth::id())->first();//orderby desc very imp as user may have cancelde paypal process after entry inside our system's  order table
        $order->paypal_paymentid = $payment_id;//add other ids as well like payer id but it is not calculated at this time i think check it once.
        $order->total_amount = $r->amount;
        $order->save();

        return response()->json([$payment->toArray(), 'approval_url' => $payment->getApprovalLink()], 200);
    }


    /*
        all payments that are remaining to be paid to song owner artist[middleware-admin]. 
    */
    public function getPayments()
    {

    }

    /*
        payment to artist for their songs sold[middleware-admin] 
    */
    public function payToArtist()
    {
        
    }

}
