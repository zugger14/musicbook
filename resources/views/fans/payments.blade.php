@extends('main')
@section('title', '| Payments & Orders')
@section('navbar_title', 'Music Book | Payments')

@section('content')
<div class="container-fluid" style="max-width: 1200px; margin: 30px auto;">
    <div class="row">
        <!-- Gateway Status Card -->
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <span>💳 Payment Gateway Information</span>
                    <span class="pull-right">
                        @if($is_configured)
                            <span class="badge" style="background: rgba(16, 185, 129, 0.2); color: #10b981; border: 1px solid #10b981;">● Online / Active</span>
                        @else
                            <span class="badge" style="background: rgba(245, 158, 11, 0.2); color: #f59e0b; border: 1px solid #f59e0b;">● Sandbox / Configuration Needed</span>
                        @endif
                    </span>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h4 style="margin-top: 0; color: #fff;">PayPal & Credit Card Billing</h4>
                            <p style="color: #94a3b8; font-size: 14px;">
                                MusicBook supports direct music purchases via PayPal and credit card transactions. 
                                When purchasing songs from independent artists, transactions are processed securely through PayPal Sandbox/Production APIs.
                            </p>
                            @if(!$is_configured)
                                <div style="background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.3); border-radius: 10px; padding: 12px 16px; margin-top: 15px;">
                                    <strong style="color: #fbbf24;">⚠️ Note:</strong> 
                                    <span style="color: #fef3c7; font-size: 13px;">
                                        PayPal API credentials are not yet set in <code>.env</code>. To enable live sandbox checkout, provide <code>PAYPAL_CLIENT_ID</code> and <code>PAYPAL_CLIENT_SECRET</code>.
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-4 text-center">
                            <div style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 12px; padding: 20px;">
                                <div style="font-size: 32px; margin-bottom: 8px;">🔒</div>
                                <div style="font-weight: 700; color: #fff; font-size: 16px;">SSL Encrypted Checkout</div>
                                <div style="color: #64748b; font-size: 12px; margin-top: 4px;">Direct payment to artist with automated audio license generation</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders & Transactions List -->
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <span>🛍️ Your Order & Purchase History</span>
                    <span class="pull-right badge" style="background: rgba(139, 92, 246, 0.2); color: #c4b5fd;">
                        {{ count($orders) }} Total Orders
                    </span>
                </div>
                <div class="panel-body">
                    @if(count($orders) > 0)
                        <div class="table-responsive">
                            <table class="table" style="color: #cbd5e1; margin-bottom: 0;">
                                <thead>
                                    <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.1); color: #94a3b8; font-size: 13px;">
                                        <th>Order #</th>
                                        <th>Track Title</th>
                                        <th>Amount</th>
                                        <th>Transaction ID</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                        <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.05); font-size: 14px;">
                                            <td>#{{ $order->id }}</td>
                                            <td style="font-weight: 600; color: #fff;">
                                                {{ $order->song ? $order->song->title : 'Audio Track' }}
                                            </td>
                                            <td>${{ $order->total_amount ?: '0.00' }}</td>
                                            <td>
                                                <code style="background: rgba(255, 255, 255, 0.05); color: #c4b5fd; padding: 3px 6px; border-radius: 4px;">
                                                    {{ $order->paypal_paymentid ?: 'Manual' }}
                                                </code>
                                            </td>
                                            <td>
                                                @if($order->paid_order == 1)
                                                    <span class="badge" style="background: rgba(16, 185, 129, 0.2); color: #10b981;">Paid</span>
                                                @else
                                                    <span class="badge" style="background: rgba(245, 158, 11, 0.2); color: #f59e0b;">Pending</span>
                                                @endif
                                            </td>
                                            <td>{{ $order->created_at ? $order->created_at->format('M d, Y') : 'N/A' }}</td>
                                            <td>
                                                @if($order->paid_order == 1 && $order->paypal_paymentid)
                                                    <a href="{{ route('songs.download', $order->paypal_paymentid) }}" class="btn btn-xs btn-primary">
                                                        Download
                                                    </a>
                                                @else
                                                    <span style="color: #64748b; font-size: 12px;">Processed</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center" style="padding: 40px 20px;">
                            <div style="font-size: 40px; margin-bottom: 15px;">🎵</div>
                            <h4 style="color: #fff; margin-bottom: 8px;">No Song Orders Yet</h4>
                            <p style="color: #64748b; max-width: 480px; margin: 0 auto 20px; font-size: 14px;">
                                Browse unreleased tracks from artists in the catalog to purchase high quality audio files and support creators directly.
                            </p>
                            <a href="{{ route('fan.home') }}" class="btn btn-primary">
                                Browse Music Feed
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
