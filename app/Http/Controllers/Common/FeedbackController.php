<?php

namespace BBCMS\Http\Controllers\Common;

use BBCMS\Http\Controllers\BaseController;
use BBCMS\Http\Requests\Common\FeedbackRequest;

class FeedbackController extends BaseController
{
    const what = 'BixBite';
    const version = '0.9 beta';

    public function send(FeedbackRequest $request)
    {
        $complete = $this->mail(
        	config('mail.from.address'),
        	'Письмо с сайта '.setting('system.app_name'),
        	'Посетитель сайта <b>'.$request->feedback_name.'</b> отправил сообщение следующего содержания: <br><br>'
            .$request->feedback_text . '<br><br>Просит Вас связаться: <b>' . $request->feedback_email . '</b>.<br><br>'
            // .'Его ip: <a href="http://www.nic.ru/whois/?ip='.request()->ip().'">'.request()->ip().'</a>'
    	);

        if ($request->ajax()) {
            if (! $complete) {
                return response()->json([
                    'status' => false,
                    'message' => 'Не могу отправить письмо !!!',
                ], 500);
            }
            return response()->json([
                'status' => true,
                'message' => "Спасибо за сообщение!<br>Вы получите ответ в ближайшее время.",
            ], 200);
        }

        if (! $complete) {
            return redirect()->to(url()->previous())->withErrors('Не могу отправить письмо !!!');
        }

        return redirect()->to(url()->previous())->withStatus('Спасибо за сообщение!<br>Вы получите ответ в ближайшее время.');
    }

    protected function mail($to, $subject, $message, $mail_from = false, $filename = false) {

        $mail_from = (! $mail_from) ? 'mailbot@'.str_replace('www.', '', $_SERVER['SERVER_NAME']) : $mail_from;
        $uniqid = md5(uniqid(time()));
        $eol = PHP_EOL;

        $headers = 'From: ' . $mail_from . $eol;
        $headers .= 'Reply-to: ' . $mail_from . $eol;
        $headers .= 'Return-Path: ' . $mail_from . $eol;
        $headers .= 'Message-ID: <'.$uniqid.'@'.$_SERVER['SERVER_NAME'].">".$eol;
        $headers .= 'MIME-Version: 1.0' . $eol;
        $headers .= 'Date: '.gmdate('D, d M Y H:i:s', time()).$eol;
        $headers .= 'X-Priority: 3' . $eol;
        $headers .= 'X-MSMail-Priority: Normal' . $eol;
        $headers .= 'X-Mailer: '.self::what.' : '.self::version . $eol;
        $headers .= 'X-MimeOLE: '.self::what.' : '.self::version . $eol;
        $headers .= 'Content-Type: multipart/mixed;boundary="----------'.$uniqid.'"'.$eol;
        $headers .= '------------'.$uniqid.$eol;
        $headers .= 'Content-Type: text/html;charset=UTF-8'.$eol;
        $headers .= 'Content-Transfer-Encoding: 8bit';

        if (is_file($filename)) {
            $file		=	fopen($filename, 'rb');
            $message	.=	"\n".'------------'.$uniqid."\n";
            $message	.=	'Content-Type: application/octet-stream;name="'.basename($filename).'"'."\n";
            $message	.=	'Content-Transfer-Encoding: base64'."\n";
            $message	.=	'Content-Disposition: attachment;';
            $message	.=	'filename="'.basename($filename).'"'."\n\n";
            $message	.=	chunk_split(base64_encode(fread($file, filesize($filename))))."\n";
        }

        return mail($to, $subject, $message, $headers) ?? false;
    }
}

/*
$params = [
    'value' => 'Значение',
];

\Illuminate\Support\Facades\Mail::to('rusiq@list.ru')->send(new \BBCMS\Mail\Feedback($params));
 */
