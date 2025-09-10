<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فاتورة - {{ $invoice->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        p {
            margin: 0;
        }

        body {
            font-family: 'almarai', sans-serif !important;
            font-size: 14px;
            line-height: 1.5;
            color: #333;
            direction: rtl;
            text-align: right;
            background: white;
            padding: 40px;
        }


        .watermark {
            position: absolute;
            /*width: 100%;*/
            /*text-align: center;*/
            top: 50%;
            left: 50%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }

        table th {
            background: none;
            border: none;
            border-bottom: 1px solid #ddd;
            padding: 15px 10px;
            text-align: right;
            font-size: 14px;
            font-weight: 700;
            color: #666;
            text-transform: uppercase;
            background: #f5f5f5;
        }

        table td {
            border: none;
            border-bottom: 1px solid #eee;
            padding: 15px 10px;
            text-align: right;
            font-size: 14px;
            color: #666;
        }

        @page {
            footer: page-footer;
        }
    </style>
</head>

<body>
@php
    $settings = app(\App\Settings\GeneralSettings::class);
    $logo = $settings?->site_logo ? "storage/$settings->site_logo" : "images/logo.png";
@endphp
<div class="watermark">
    <img src="{{ $logo }}" alt="logo"
         style="transform: translate(-50%, -50%) rotate(-45deg) scale(1.5); opacity: 0.07"/>
</div>
<div>
    <!-- Header -->
    <div style="margin-bottom: 20px;">
        <div style="text-align: center; width: 120px; float:left;">
            <img src="{{ $logo }}" style="width: 80px; margin-bottom: 10px;">
            <p style="font-size: 20px; font-weight: 600; color: #666; margin-bottom: 5px;">
                {{ $settings->site_name }}
            </p>
            @if($settings->site_phone)
                <p style="font-size: 14px">
                    هاتف: {{$settings->site_phone}}
                </p>
            @endif

        </div>
        <div style="text-align: right; width: 50%; float:right;">
            <div style="font-size: 36px; font-weight: 700; color: #333; margin-bottom: 10px;">فاتورة</div>
            <div style="font-size: 14px; color: #666; line-height: 1.6;">
                <div>تاريخ الفاتورة: {{ $invoice->issue_date->format('d/m/Y') }}</div>
                <div>رقم الفاتورة: {{ $invoice->id }}</div>
            </div>
        </div>
    </div>

    <!-- Bill To Section -->
    <div style="margin-bottom: 20px;">
        <div style="font-size: 20px; font-weight: 700; color: #333; margin-bottom: 15px;">فاتورة إلى:</div>
        <div style="font-size: 14px; color: #666; line-height: 1.6;">
            <div style="margin-bottom: 5px;">{{ $invoice->customer->name }}</div>
            <div style="margin-bottom: 5px;">{{ $invoice->customer->address ?? '' }}</div>
            <div style="margin-bottom: 5px;">{{ $invoice->customer->phone ?? '' }}</div>
            @if($invoice->customer->settings->show_total_remaining_in_invoice)
                <div style="margin-bottom: 5px;">اجمالي المتبقي:
                    <b>{{$invoice->customer->invoices->sum(fn ($item) => $item->total_amount - $item->paid_amount)}}
                        د.ل</b></div>
            @endif
        </div>
    </div>

    <!-- Items Table -->
    <table>
        <thead>
        <tr>
            <th>الصنف</th>
            <th style="text-align: center;">الكمية</th>
            <th style="text-align: center;">السعر</th>
            <th style="text-align: center;">المجموع</th>
        </tr>
        </thead>
        <tbody>
        @forelse($invoice->items as $item)
            <tr>
                <td>{{ $item->description }}</td>
                <td style="text-align: center;">{{ $item->quantity ?? 1 }}</td>
                <td style="text-align: center;">{{ number_format($item->unit_price ?? 0, 2) }} د.ل</td>
                <td style="text-align: center;">
                    {{ number_format(($item->quantity ?? 1) * ($item->unit_price ?? 0), 2) }} د.ل
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" style="color: #999; font-style: italic; text-align: center;">لا توجد أصناف
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <div style="border-bottom: 1px solid #ddd; margin: 20px 0;"></div>

    <!-- Totals Section -->
    <div style="clear: both;"></div>
    <div>
        <div style="width: 300px; text-align: right; float: left;">
            @if ($invoice->discount > 0)
                <div style="font-size: 14px; color: #333; padding: 5px 10px;">
                    <div style="width: 50%; float: right;">المجموع</div>
                    <div style="width: 50%; float: left; text-align: left;">
                        {{ number_format($invoice->subtotal_amount, 2) }} د.ل
                    </div>
                </div>
                <div style="font-size: 14px; color: #333; padding: 5px 10px;">
                    <div style="width: 50%; float: right;">الخصم</div>
                    <div style="width: 50%; float: left; text-align: left;">
                        {{ number_format($invoice->discount, 2) }} د.ل
                    </div>
                </div>
            @endif
            <div style="font-size: 16px; color: #333;background: #f5f5f5; padding: 10px; font-weight: bold;">
                <div style="width: 50%; float: right;">الإجمالي</div>
                <div style="width: 50%; float: left; text-align: left; ">
                    {{ number_format($invoice->total_amount, 2) }}
                    د.ل
                </div>
            </div>
            <div style="font-size: 14px; color: #333; padding: 10px;">
                <div style="width: 50%; float: right;">المبلغ المدفوع</div>
                <div style="width: 50%; float: left; text-align: left;">
                    {{ number_format($invoice->paid_amount, 2) }} د.ل
                </div>
            </div>
            <div style="font-size: 14px; color: #333; border-top: 2px solid #ddd; padding: 10px;">
                <div style="width: 50%; float: right;">المتبقي</div>
                <div style="width: 50%; float: left; text-align: left;">
                    {{ number_format($invoice->total_amount - $invoice->paid_amount, 2) }}
                    د.ل
                </div>
            </div>
        </div>
    </div>
</div>

<htmlpagefooter name="page-footer">
    <div style="float: right; width: 33%; text-align: right">{PAGENO}</div>
    <div style="float: right; width: 33%; text-align: center"></div>
    <div style="float: left; width: 33%; text-align: left">{{ now()->format('Y/m/d') }}</div>
</htmlpagefooter>
</body>

</html>
