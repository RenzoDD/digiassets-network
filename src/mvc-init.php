<?php
/*
 * Copyright 2021 (c) Renzo Diaz
 * Licensed under MIT License
 * Configuration file
 */

define("__MODEL__", __DIR__ . "/mvc/models");
define("__VIEW__", __DIR__ . "/mvc/views");
define("__CONTROLLER__", __DIR__ . "/mvc/controllers");

define("__LIBS__", __DIR__ . "/libs");

define("__ASSETS__", __DIR__ . "/assets");
define("__IMG__", __DIR__ . "/assets/img");
define("__FONTS__", __DIR__ . "/assets/fonts");

define("__SERVER__", $_SERVER["SERVER_NAME"]);
define("__ROUTE__", $_SERVER['REQUEST_URI']);

if (__SERVER__ === "localhost")
    error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
else
    error_reporting(0);

date_default_timezone_set("UTC");

function MetaHeaders($title = "", $description = "")
{
    $url   = "https://www.example.com/";
    $image = "https://www.example.com/assets/img/thumbnail.png";
    $keywords = "keyword 1, keword 2, keyword 3";
    $twitter = "@twitter";
    $color = "#002352";

    $result = "<meta charset=\"UTF-8\">";
    $result .= "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">";


    if ($description != "")
        $result .= "<meta name=\"description\" content=\"$description\">";

    $result .= "<meta name=\"url\" content=\"$url\">";
    $result .= "<meta name=\"theme-color\" content=\"$color\">";
    $result .= "<meta property=\"og:type\" content=\"website\">";
    $result .= "<meta property=\"og:url\" content=\"$url\">";

    if ($title != "")
        $result .= "<meta property=\"og:title\" content=\"$title\">";

    if ($description != "")
        $result .= "<meta property=\"og:description\" content=\"$description\">";

    $result .= "<meta property=\"og:image\" content=\"$image\">";

    $result .= "<meta name=\"twitter:card\" content=\"summary\">";

    if ($twitter != "") {
        $result .= "<meta name=\"twitter:site\" content=\"$twitter\">";
        $result .= "<meta name=\"twitter:creator\" content=\"$twitter\">";
    }

    $result .= "<meta property=\"twitter:url\" content=\"$url\">";

    if ($title != "")
        $result .= "<meta property=\"twitter:title\" content=\"$title\">";

    if ($description != "")
        $result .= "<meta property=\"twitter:description\" content=\"$description\">";

    $result .= "<meta property=\"twitter:image\" content=\"$image\">";
    $result .= "<meta name=\"keywords\" content=\"$keywords\" />";

    return $result;
}
function GoogleAnalytics($pagename = "")
{
    $result = "";
    if (__SERVER__ != "localhost") {
        if ($pagename != "")
            $pagename = ",{'page_path': '$pagename'}";

        $result .= '<script async src="https://www.googletagmanager.com/gtag/js?id=' . GA_CODE . '"></script>';
        $result .= "<script>";
        $result .= "     window.dataLayer = window.dataLayer || [];";
        $result .= "     function gtag(){dataLayer.push(arguments);}";
        $result .= "     gtag('js', new Date());";
        $result .= "     gtag('config', '" . GA_CODE . "'$pagename);";
        $result .= "</script>";
    }
    return $result;
}

function DateFormat($date = "now", $format = "datetime", $zone = 0)
{
    $now = time();
    $today = strtotime(gmdate("Y-m-d 00:00:00", $now + (3600 * $zone)));

    if ($format == "unix") {
        if ($date == "now")
            return $now;
        else if ($date == "today")
            return $today;
        else if (gettype($date) == "string")
            return strtotime($date);

        return $date;
    } else {
        if (gettype($date) === "string")
            $date = strtotime($date);

        $unix = $date == "now"   ?  $now : $date;
        $unix = $date == "today" ?  $today : $unix;

        $format = $format == "date"     ? "Y-m-d"       : $format;
        $format = $format == "datetime" ? "Y-m-d H:i:s" : $format;

        return gmdate($format, $unix + (3600 * $zone));
    }
}

function PaginationStart($actual_page, $pages_to_show, $total_pages)
{
    $s = $actual_page - floor($pages_to_show / 2);
    $s = ($s <= 0) ? 1 : $s;

    $f = $s + ($pages_to_show - 1);
    $f = ($f > $total_pages) ? $total_pages : $f;
    $s = $f - ($pages_to_show - 1);

    $s = ($s <= 0) ? 1 : $s;

    return ["start" => $s, "end" => $f];
}

if (!function_exists("str_starts_with")) {
    function str_starts_with($string, $startString)
    {
        $len = strlen($startString);
        return (substr($string, 0, $len) === $startString);
    }
}

class reCAPTCHA
{
    static function V2()
    {
        /*
         *  <head>
         *      <script src="https://www.google.com/recaptcha/api.js" async defer></script>
         *  </head>
         *
         *  <div class="g-recaptcha d-inline-block" data-callback="recaptchaCallback" data-sitekey="<?php echo PK_reCAPTCHA_v2; ?>"></div>
         *  <script>
         *      function recaptchaCallback() {
         *          $('#button-id').removeAttr('disabled');
         *      };
         *  </script>
         */
        if (__SERVER__ == "localhost")
            return true;

        if (isset($_POST["g-recaptcha-response"])) {
            $secretKey = SK_reCAPTCHA_v2;

            $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secretKey) .  '&response=' . urlencode($_POST["g-recaptcha-response"]);
            $response = file_get_contents($url);
            $responseKeys = json_decode($response, true);

            if ($responseKeys["success"])
                return true;
        }

        return false;
    }
    static function V3()
    {
        /*
         *  <head>
         *      <script src="https://www.google.com/recaptcha/api.js?render=<?php echo PK_reCAPTCHA_v3; ?>"></script>
         *  </head>
         *
         *  <script>
         *      $('#form-id').submit(function(event) {
         *          var code = "send_dgb";
         *          event.preventDefault();
         *  
         *          grecaptcha.ready(function() {
         *              grecaptcha.execute('<?php echo PK_reCAPTCHA_v3; ?>', {action: code}).then(function(token) {
         *                  $('#form-id').prepend('<input type="hidden" name="token" value="' + token + '">');
         *                  $('#form-id').prepend('<input type="hidden" name="action" value="' + code + '">');
         *                  $('#form-id').unbind('submit').submit();
         *              });;
         *          });
         *      });
         *  </script>
         */
        if (__SERVER__ == "localhost")
            return true;

        if (isset($_POST['token'], $_POST['action'])) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('secret' => SK_reCAPTCHA_v3, 'response' => $_POST['token'])));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);
            $captcha = json_decode($response, true);

            if ($captcha["success"] == '1' && $captcha["action"] == $_POST['action'] && $captcha["score"] >= 0.9)
                return true;
        }

        return false;
    }
}

class TextCaptcha
{
    public static function Generate($length = 5)
    {
        function generate_string($strength = 10)
        {
            $input = 'AB1C2DEFG3HJK4LMNP6QRS50TUV7WX8Y9Z';
            $input_length = strlen($input);
            $random_string = '';
            for ($i = 0; $i < $strength; $i++) {
                $random_character = $input[mt_rand(0, $input_length - 1)];
                $random_string .= $random_character;
            }
            return $random_string;
        }

        $image = imagecreatetruecolor(200, 50);
        imageantialias($image, true);

        $colors = [];

        $red = rand(125, 175);
        $green = rand(125, 175);
        $blue = rand(125, 175);

        for ($i = 0; $i < 5; $i++)
            $colors[] = imagecolorallocate($image, $red - 20 * $i, $green - 20 * $i, $blue - 20 * $i);

        imagefill($image, 0, 0, $colors[0]);

        for ($i = 0; $i < 10; $i++) {
            imagesetthickness($image, rand(2, 10));
            $line_color = $colors[rand(1, 4)];
            imagerectangle($image, rand(-10, 190), rand(-10, 10), rand(-10, 190), rand(40, 60), $line_color);
        }

        $black = imagecolorallocate($image, 0, 0, 0);
        $white = imagecolorallocate($image, 255, 255, 255);
        $textcolors = [$black, $white];

        $fonts = [
            __FONTS__ . '/captcha.ttf'
        ];

        $string_length = 5;
        $captcha_string = generate_string($string_length);

        $_SESSION['captcha'] = $captcha_string;

        for ($i = 0; $i < $string_length; $i++) {
            $letter_space = 170 / $string_length;
            $initial = 15;

            imagettftext($image, 24, rand(-15, 15), $initial + $i * $letter_space, rand(25, 45), $textcolors[rand(0, 1)], $fonts[array_rand($fonts)], $captcha_string[$i]);
        }

        return $image;
    }
    public static function Validate($guess = null)
    {
        if (__SERVER__ == "localhost")
            return true;

        if ($guess == null)
            $guess = $_POST["captcha"];

        return strtoupper($guess) == $_SESSION['captcha'];
    }
    public static function Randomize()
    {
        $_SESSION['captcha'] = md5(rand());
    }
}

class QR
{
    public static function Create($data, $filepath, $logopath)
    {
        QRcode::png($data, $filepath, QR_ECLEVEL_H, 5, 1);

        $QR = imagecreatefrompng($filepath);
        $logo = imagecreatefromstring(file_get_contents($logopath));

        imagecolortransparent($logo, imagecolorallocate($logo, 0, 0, 0));
        imagealphablending($logo, false);
        imagesavealpha($logo, true);

        $QR_width = imagesx($QR);
        $QR_height = imagesy($QR);

        $logo_width = imagesx($logo);
        $logo_height = imagesy($logo);

        $logo_qr_width = $QR_width / 3;
        $scale = $logo_width / $logo_qr_width;
        $logo_qr_height = $logo_height / $scale;

        $result = imagecreatetruecolor($QR_width, $QR_width);
        imagecopyresampled($result, $QR, 0, 0, 0, 0, $QR_width, $QR_height, $QR_width, $QR_height);
        imagecopyresampled($result, $logo, $QR_width / 3, $QR_height / 3, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);

        imagepng($result, $filepath);
    }
}

class Bootstrap
{
    static function Icon($icon)
    {
        return "<i class='bi bi-$icon'></i>";
    }

    static function Alert($message, $type = "primary", $dissmiss = false, $icon = "")
    {
        $icon = $icon != "" ? self::Icon($icon) : "";

        $close = $dissmiss ? "<button type='button' class='btn-close' data-bs-dismiss='alert'></button>" : "";
        $dissmiss = $dissmiss ? " alert-dismissible fade show" : "";

        return "
            <div class='alert alert-$type $dissmiss' role='alert'>
                $icon
                $message
                $close
            </div>";
    }

    static function NavbarItem($text, $enabled = false, $href = "#", $active = false, $items = null)
    {
        if ($items == null) {
            $active   = $enabled && $active ? " active"   : "";
            $enabled = !$enabled            ? " disabled" : "";

            $class = "class='nav-link$active$enabled'";
            if (str_starts_with($href, "https://") || str_starts_with($href, "http://"))
                $href = "href='$href' target='_blank'";
            else
                $href = "href='$href'";

            print '<li class="nav-item my-auto">';
            print "    <a $class $href>$text</a>";
            print '</li>';
        } else {
            $active   = $enabled && $active ? " active"   : "";
            $enabled = !$enabled            ? " disabled" : "";

            if (str_starts_with($href, "https://") || str_starts_with($href, "http://"))
                $href = "href='$href' target='_blank'";
            else
                $href = "href='$href'";

            print "<li class='nav-item dropdown my-auto'>";
            print "     <a class='nav-link dropdown-toggle $active$enabled' href='$href' id='navbarDropdown' role='button' data-bs-toggle='dropdown'>";
            print           $text;
            print "     </a>";
            print "     <ul class='dropdown-menu'>";

            foreach ($items as $i) {
                if (str_starts_with($i["href"], "https://") || str_starts_with($i["href"], "http://"))
                    $i["href"] = "href='" . $i["href"] . "' target='_blank'";
                else
                    $i["href"] = "href='" . $i["href"] . "'";
                print "<li class='text-center'><a class='dropdown-item' " . $i["href"] . ">" . $i["text"] . "</a></li>";
            }
            print "     </ul>";
            print "</li>";
        }
    }
}

class HTTP
{
    public static function Get($url)
    {
        return json_decode(file_get_contents($url));
    }
    public static function Post($url, $data)
    {
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data)
            )
        );
        $context  = stream_context_create($options);
        return json_decode(file_get_contents($url, false, $context));
    }
}

function routeCheck($template)
{
    $arg = explode("?", __ROUTE__)[0];
    $arg = explode("#", $arg)[0];

    $arg = explode("/", $arg);
    $tmp = explode("/", $template);

    $arg = array_filter($arg);
    $tmp = array_filter($tmp);

    $min = min(sizeof($arg), sizeof($tmp));
    $max = max(sizeof($arg), sizeof($tmp));

    $arg = array_values($arg);
    $tmp = array_values($tmp);

    $get = [];

    for ($i = 0; $i < $min; $i++) {
        //$tmp[$i]  $arg[$i]
        if ($tmp[$i][0] === ':')    //Variable
        {
            if ($tmp[$i][strlen($tmp[$i]) - 1] === '?')
                $len = strlen($tmp[$i]) - 2;
            else
                $len = strlen($tmp[$i]) - 1;
            $get[substr($tmp[$i], 1, $len)] = $arg[$i];
        } else {
            if ($tmp[$i] !== $arg[$i]) return null;
        }
    }

    if ($i <= $max && $max === sizeof($tmp)) {
        for ($i = $i; $i < $max; $i++) {
            if ($tmp[$i][strlen($tmp[$i]) - 1] !== '?') // Optional
                return null;
            else if ($tmp[$i][0] === ':')    //Variable
                $get[substr($tmp[$i], 1, strlen($tmp[$i]) - 2)] = null;
        }
    } else
        return null;

    return $get;
}

function route($template, $callback)
{
    $get = routeCheck($template);
    if ($get !== null) {
        $_GET = array_merge($_GET, $get);
        $callback();
        die(0);
    }
}

function Redirect($url)
{
    header("Location: $url");
    die(0);
}
