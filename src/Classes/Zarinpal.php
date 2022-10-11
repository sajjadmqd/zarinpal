<?php
/*
 * ZarinPal Advanced Class
 *
 * retrieved from : milad maldar
*/

namespace Sajjadmgd\Zarinpal\Classes;

class Zarinpal
{
    private function error_message($code, $desc, $cb, $request = false)
    {
        if (empty($cb) && $request === true) {
            return 'لینک بازگشت ( CallbackURL ) نباید خالی باشد';
        }

        if (empty($desc) && $request === true) {
            return 'توضیحات تراکنش ( Description ) نباید خالی باشد';
        }

        $error = array(
            '-1' => 'اطلاعات ارسال شده ناقص است.',
            '-2' => 'IP و يا مرچنت كد پذيرنده صحيح نيست',
            '-3' => 'با توجه به محدوديت هاي شاپرك امكان پرداخت با رقم درخواست شده ميسر نمي باشد',
            '-4' => 'سطح تاييد پذيرنده پايين تر از سطح نقره اي است.',
            '-11' => 'درخواست مورد نظر يافت نشد.',
            '-12' => 'امكان ويرايش درخواست ميسر نمي باشد.',
            '-21' => 'هيچ نوع عمليات مالي براي اين تراكنش يافت نشد',
            '-22' => 'تراكنش نا موفق ميباشد',
            '-33' => 'رقم تراكنش با رقم پرداخت شده مطابقت ندارد',
            '-34' => 'سقف تقسيم تراكنش از لحاظ تعداد يا رقم عبور نموده است',
            '-40' => 'اجازه دسترسي به متد مربوطه وجود ندارد.',
            '-41' => 'اطلاعات ارسال شده مربوط به AdditionalData غيرمعتبر ميباشد.',
            '-42' => 'مدت زمان معتبر طول عمر شناسه پرداخت بايد بين 30 دقيه تا 45 روز مي باشد.',
            '-54' => 'درخواست مورد نظر آرشيو شده است',
            '100' => 'عمليات با موفقيت انجام گرديده است.',
            '101' => 'عمليات پرداخت موفق بوده و تایید تراكنش قبلا انجام شده است.',
        );

        if (array_key_exists("{$code}", $error)) {
            return $error["{$code}"];
        } else {
            return 'خطای نامشخص هنگام اتصال به درگاه زرین پال';
        }
    }

    public function request($merchantID, $amount, $callbackURL, $description = '', $sandBox = false, $zarinGate = false): object
    {
        $zarinGate = ($sandBox == true) ? false : $zarinGate;
        $upay     = ($sandBox == true) ? 'sandbox' : 'www';

        $data = array(
            'MerchantID'     => $merchantID,
            'Amount'         => $amount / 10,
            'Description'    => $description,
            'CallbackURL'    => $callbackURL,
        );

        $jsonData = json_encode($data);
        $ch = curl_init("https://{$upay}.zarinpal.com/pg/rest/WebGate/PaymentRequest.json");
        curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v1');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($jsonData)));

        $result = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        $result = json_decode($result, true);

        if ($err) {
            $status = 0;
            $message = 'cURL Error #:' . $err;
            $authority = '';
            $startPay = '';
            $startPayUrl = '';
        } else {
            $status = (isset($result['Status']) && $result['Status'] != '') ? $result['Status'] : 0;
            $message = $this->error_message($status, $description, $callbackURL, true);
            $authority = (isset($result['Authority']) && $result['Authority'] != '') ? $result['Authority'] : '';
            $startPay = (isset($result['Authority']) && $result['Authority'] != '') ? "https://{$upay}.zarinpal.com/pg/StartPay/$authority" : '';
            $startPayUrl = (isset($zarinGate) && $zarinGate == true) ? "{$startPay}/ZarinGate" : $startPay;
        }

        return (object) [
            'status' => $status,
            'message' => $message,
            'startPay' => $startPayUrl,
            'authority' => $authority
        ];
    }

    public function verify($merchantID, $authority, $amount): object
    {
        $data = array('merchant_id' => $merchantID, 'authority' => $authority, 'amount' => $amount * 10);
        $jsonData = json_encode($data);
        $ch = curl_init('https://api.zarinpal.com/pg/v4/payment/verify.json');
        curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v1');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($jsonData)));

        $result = curl_exec($ch);
        $err     = curl_error($ch);
        curl_close($ch);

        $result = json_decode($result, true)['data'];

        if ($err) {
            $status = 0;
            $message = 'cURL Error #:' . $err;
            $status = '';
            $refID = '';
        } else {
            $status = (isset($result['code']) && $result['code'] != '') ? $result['code'] : 0;
            $refID = (isset($result['ref_id']) && $result['ref_id'] != '') ? $result['ref_id'] : '';
            $message = $this->error_message($status, '', '', false);
        }

        return (object) [
            'status' => $status,
            'message' => $message,
            'amount' => $amount,
            'refID' => $refID
        ];
    }
}
