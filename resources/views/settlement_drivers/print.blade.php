<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{setting('app_name')}} | {{setting('app_short_description')}}</title>
    {{-- <link rel="icon" type="image/png"   href="{{ url('images/logo.png') }}"> --}}
    {{-- <title>{{ config('app.name') }}</title> --}}
    <link rel="icon" type="image/png" href="{{$app_logo}}"/>
    <link rel="stylesheet" type="text/css" href="{{ url('css/sheets-of-paper.css') }}">

</head>

<body class="document">

    <style>
        /* arabic */
        @font-face {
            font-family: 'Tajawal';
            font-style: normal;
            font-weight: 400;
            src: url( {{ url('fonts/Tajawal/Tajawal-Medium.ttf') }} );
        }

        body {
            direction: rtl;
            font-family: Tajawal;
        }

        .header {
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #000;
            margin-bottom: 20px;
        }

        .header img {
            height: 80px;
        }

        .header .title {
            font-size: 24px;
            text-align: right;
            padding-right: 10px;
            margin: 0;
        }

        .page-title {
            text-align: center;
            font-size: 18px;
            text-decoration: underline;
            text-underline-position: under;
        }

        .info {
            display: flex;
            justify-content: space-between;
        }

        .info ul {
            padding: 0;
            list-style: none;
            max-width: 50%;
        }

        .info ul li {
            line-height: 1.5;
        }

        .table {
            width: 100%;
            text-align: center;
            border-collapse: collapse;
            page-break-inside : auto;
        }

        .table th , .table td {
            padding: 7px;
            border: 1px solid #ddd;
          }
        .footer ul {
            padding: 0;
            list-style: none;
        }

        .footer ul li {
            line-height: 1.5;
        }
    </style>

    <div class="page">

        <div class="header">
            <img src="{{$app_logo}}" />
        </div>

        <h2 class="page-title">فاتورة تصكير حساب مندوب</h2>

        <div class="info">
            <ul>
                <li><b>رقم الفاتورة : </b><bdi>{{ $settlement->id }}</bdi></li>
                @if($settlement->created_at)
                    <li><b>تاريخ التسليم: </b><bdi>{{ $settlement->created_at->format('Y/m/d') }}</bdi></li>
                @endif
            </ul>
            <ul>
                <li><b>رقم العضوية : </b><bdi>{{ $settlement->driver_id }}</bdi></li>
                <li><b>اسم المندوب : </b><bdi>{{ $settlement->driver->name }}</bdi></li>
            </ul>
        </div>

        <div class="footer">

            <table class="table" style="margin-top: 20px;">
                <thead>
                    <tr style="background-color: #bbb;">
                        <th>#</th>
                        <th>رقم الطلبية</th>
                        <th>عمولة المندوب</th>
                        <th>قيمة الطلبية</th>
                        <th>طريقة الدفع</th>
                        <th>التاريخ</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($settlement->orders as $order)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->delivery_fee }}</td>
                            <td>{{ $order->payment->price }}</td>
                            <td>{{ $order->payment->method }}</td>
                            <td><bdi>{{ $order->created_at->format('Y-m-d g:ia') }}</bdi></td>
                        </tr>
                    @endforeach

                </tbody>
            </table>

            <table class="table" style="margin-top: 20px;">
                <thead>
                    <tr style="background-color: #bbb;">
                        <th>العدد</th>
                        <th>الإجمالي</th>
                        <th>الموظف</th>
                        <th>التاريخ</th>
                    </tr>
                </thead>
                <tbody>

                    <tr>
                        <td>{{ $settlement->count }}</td>
                        <td>{{ $settlement->amount }} <bdi>د.ل</bdi></td>
                        <td><bdi>{{ Auth::user()->name }}</bdi></td>
                        <td><bdi>{{ date('Y-m-d g:ia') }}</bdi></td>
                    </tr>

                </tbody>
            </table>

        </div>

    </div>


    <script>
        print();
        window.onafterprint = function(e) { window.close() };
    </script>

</body>

</html>