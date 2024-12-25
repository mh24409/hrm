<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ localize('id_card_print') }}</title>
    <!-- App favicon -->
    <link rel="shortcut icon" class="favicon_show" href="{{ app_setting()->favicon }}">
</head>

<html>

<body style="font-family: Open Sans, sans-serif;font-size:100%;font-weight:400;line-height:1.4;color:#000;">
    <div
        style="max-width:300px; height: 430px; margin:50px auto 10px;background-color:#fff;-webkit-border-radius:3px;-moz-border-radius:3px;border-radius:3px;-webkit-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);-moz-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24); position: relative;">
        <div
            style="position: absolute; margin: auto; top: 0; right: 0; left: 0; width: 300px; height: 112px; background-color: #15158f; -webkit-print-color-adjust: exact; ">
            <h3 style="text-align: center; font-size: 24px; color: #fff; margin: 16px;">{{ app_setting()->title }}</h3>
            <img src="{{ asset('storage/' . $dbData->profile_img_location) }}" alt=""
                style="position: absolute; margin: auto; top: 59px; right: 0; left: 0; width: 100px; height: 100px; border-radius: 50%;  border: 4px solid #643770; background: white; overflow: hidden;">
        </div>
        <table style="top: 178px; position: absolute; padding: 10px 25px; font-size: 14px">
            <tr>
                <td>{{ localize('name') }}</td>
                <td>:</td>
                <td>{{ $dbData?->full_name }}</td>
            </tr>

            <tr>
                <td>{{ localize('position') }}</td>
                <td>:</td>
                <td>{{ $dbData->position?->position_name }}</td>
            </tr>
            <tr>
                <td>{{ localize('email') }}</td>
                <td>:</td>
                <td>{{ $dbData?->email }}</td>
            </tr>
            <tr>
                <td>{{ localize('contact') }}</td>
                <td>:</td>
                <td>
                    <small>
                        {{ $dbData?->phone }}
                    </small>
                </td>
            </tr>
        </table>
        <table style="position: absolute; bottom: 65px; right: 44px;">
            <tr>
                <td style="margin: 0px;">
                    <img src="{{ asset('assets/idcard/signature.jpg') }}" alt=""
                        style="height: 21px; width: 100px;">
                </td>
            </tr>
            <tr>
                <td style="margin: 0px; border-top:1px solid #000; font-size: 12px; text-align: center;">
                    {{ localize('signature') }}
                </td>
            </tr>

        </table>
        <div
            style="background: #15158f; -webkit-print-color-adjust: exact;  position: absolute; bottom: 0; width: 100%; color: #fff;">
            <p style="padding: 0px 8px; font-size: 12px">{{ localize('address') }} : {{ app_setting()->address }}
            </p>
        </div>

    </div>
</body>

<script src="{{ asset('backend/assets/plugins/jQuery/jquery.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        window.print();
    });
</script>

</html>
