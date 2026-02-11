<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    public string $fromEmail  = 'metelicenze@gmail.com';
    public string $fromName   = 'MeTe Licenze Admin';
    public string $recipients = '';

    /**
     * The "user agent"
     */
    public string $userAgent = 'CodeIgniter';

    /**
     * The mail sending protocol: mail, sendmail, smtp
     */
    public string $protocol = 'smtp';

    /**
     * The server path to Sendmail.
     */
    public string $mailPath = '/usr/sbin/sendmail';

    /**
     * SMTP Server Hostname
     */
    public string $SMTPHost = 'smtp.gmail.com';

    /**
     * SMTP Username
     */
    public string $SMTPUser = 'metelicenze@gmail.com';

    /**
     * SMTP Password (App Password generata da Google, NON la password normale!)
     */
    public string $SMTPPass = 'rzef bxcx oauq lqws'; //11.02.2026 Rigenerata da https://myaccount.google.com/u/4/apppasswords?continue=https://myaccount.google.com/u/4/security?rapt%3DAEjHL4OcuLz0tq-UFXhGzMFgbev_OGPrjPbl_YQPUuvj0z0WBcEHd-mnvAWOMy-W-9Db6gtBYM5rUYJKgCnUw4Kwna3pKk4B-ph4VULtmX2Fpe_02yliUWE%26authuser%3D4&rapt=AEjHL4OcuLz0tq-UFXhGzMFgbev_OGPrjPbl_YQPUuvj0z0WBcEHd-mnvAWOMy-W-9Db6gtBYM5rUYJKgCnUw4Kwna3pKk4B-ph4VULtmX2Fpe_02yliUWE

    /**
     * SMTP Port (465 con SSL, oppure 587 con TLS)
     */
    public int $SMTPPort = 465;

    /**
     * SMTP Timeout (in seconds)
     */
    public int $SMTPTimeout = 10;

    /**
     * Enable persistent SMTP connections
     */
    public bool $SMTPKeepAlive = false;

    /**
     * SMTP Encryption.
     *
     * @var string 'tls' o 'ssl'
     */
    public string $SMTPCrypto = 'ssl';

    /**
     * Enable word-wrap
     */
    public bool $wordWrap = true;

    /**
     * Character count to wrap at
     */
    public int $wrapChars = 76;

    /**
     * Type of mail, either 'text' or 'html'
     */
    public string $mailType = 'html';

    /**
     * Character set (utf-8, iso-8859-1, etc.)
     */
    public string $charset = 'UTF-8';

    /**
     * Whether to validate the email address
     */
    public bool $validate = false;

    /**
     * Email Priority. 1 = highest. 5 = lowest. 3 = normal
     */
    public int $priority = 3;

    /**
     * Newline character. (Use “\r\n” to comply with RFC 822)
     */
    public string $CRLF = "\r\n";

    /**
     * Newline character. (Use “\r\n” to comply with RFC 822)
     */
    public string $newline = "\r\n";

    /**
     * Enable BCC Batch Mode.
     */
    public bool $BCCBatchMode = false;

    /**
     * Number of emails in each BCC batch
     */
    public int $BCCBatchSize = 200;

    /**
     * Enable notify message from server
     */
    public bool $DSN = false;
}
