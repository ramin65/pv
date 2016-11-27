<?php
/*
 Telegram.me/OneProgrammer
 Telegram.me/SpyGuard
                   ----[ Lotfan Copy Right Ro Rayat Konid <3 ]----
############################################################################################
# if you need Help for develop this source , You Can Send Message To Me With @SpyGuard_BOT #
############################################################################################
*/
define('API_KEY','258131711:AAFXttoH8LC9rJpLfJnndgrDtQH7FNgbfxQ');
//----######------
function makereq($method,$datas=[]){
    $url = "https://api.telegram.org/bot".API_KEY."/".$method;
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_POSTFIELDS,http_build_query($datas));
    $res = curl_exec($ch);
    if(curl_error($ch)){
        var_dump(curl_error($ch));
    }else{
        return json_decode($res);
    }
}
//##############=--API_REQ
function apiRequest($method, $parameters) {
  if (!is_string($method)) {
    error_log("Method name must be a string\n");
    return false;
  }
  if (!$parameters) {
    $parameters = array();
  } else if (!is_array($parameters)) {
    error_log("Parameters must be an array\n");
    return false;
  }
  foreach ($parameters as $key => &$val) {
    // encoding to JSON array parameters, for example reply_markup
    if (!is_numeric($val) && !is_string($val)) {
      $val = json_encode($val);
    }
  }
  $url = "https://api.telegram.org/bot".API_KEY."/".$method.'?'.http_build_query($parameters);
  $handle = curl_init($url);
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($handle, CURLOPT_TIMEOUT, 60);
  return exec_curl_request($handle);
}
//----######------
//---------
$update = json_decode(file_get_contents('php://input'));
var_dump($update);
//=========
$chat_id = $update->message->chat->id;
$message_id = $update->message->message_id;
$from_id = $update->message->from->id;
$name = $update->message->from->first_name;
$username = $update->message->from->username;
$textmessage = isset($update->message->text)?$update->message->text:'';
$txtmsg = $update->message->text;
$reply = $update->message->reply_to_message->forward_from->id;
$stickerid = $update->message->reply_to_message->sticker->file_id;
$admin = 275387751;
$step = file_get_contents("data/".$from_id."/step.txt");

//-------
function SendMessage($ChatId, $TextMsg)
{
 makereq('sendMessage',[
'chat_id'=>$ChatId,
'text'=>$TextMsg,
'parse_mode'=>"MarkDown"
]);
}
function SendSticker($ChatId, $sticker_ID)
{
 makereq('sendSticker',[
'chat_id'=>$ChatId,
'sticker'=>$sticker_ID
]);
}
function Forward($KojaShe,$AzKoja,$KodomMSG)
{
makereq('ForwardMessage',[
'chat_id'=>$KojaShe,
'from_chat_id'=>$AzKoja,
'message_id'=>$KodomMSG
]);
}
function save($filename,$TXTdata)
	{
	$myfile = fopen($filename, "w") or die("Unable to open file!");
	fwrite($myfile, "$TXTdata");
	fclose($myfile);
	}
//===========
$inch = file_get_contents("https://api.telegram.org/bot".API_KEY."/getChatMember?chat_id=@MuteStats&user_id=".$from_id);
	
	if (strpos($inch , '"status":"left"') !== false ) {
SendMessage($chat_id,"سلام 👋
🔸براي استفاده از ربات در كانال زير جوين شويد :
@MuteStats");
}
elseif(isset($update->callback_query)){
    $callbackMessage = '';
    var_dump(makereq('answerCallbackQuery',[
        'callback_query_id'=>$update->callback_query->id,
        'text'=>$callbackMessage
    ]));
    $chat_id = $update->callback_query->message->chat->id;
    
    $message_id = $update->callback_query->message->message_id;
    $data = $update->callback_query->data;
    if (strpos($data, "del") !== false ) {
    $botun = str_replace("del ","",$data);
    unlink("bots/".$botun."/index.php");
    save("data/$chat_id/bots.txt","");
    save("data/$chat_id/tedad.txt","0");
    var_dump(
        makereq('editMessageText',[
            'chat_id'=>$chat_id,
            'message_id'=>$message_id,
            'text'=>"✅ ربات شما با موفقیت حذف شد!",
            'reply_markup'=>json_encode([
                'inline_keyboard'=>[
                    [
                        ['text'=>"💠 عضویت در کانال ما!",'url'=>"https://telegram.me/MuteStats"]
                    ]
                ]
            ])
        ])
    );
 }

 else {
    var_dump(
        makereq('editMessageText',[
            'chat_id'=>$chat_id,
            'message_id'=>$message_id,
            'text'=>"خطا",
            'reply_markup'=>json_encode([
                'inline_keyboard'=>[
                    [
                        ['text'=>"💠 عضویت در کانال ما!",'url'=>"https://telegram.me/mutestats"]
                    ]
                ]
            ])
        ])
    );
 }
}

elseif ($textmessage == '🔙 برگشت') {
save("data/$from_id/step.txt","none");
var_dump(makereq('sendMessage',[
        	'chat_id'=>$update->message->chat->id,
        	'text'=>"🔃 Welcome To Main Menu",
		'parse_mode'=>'MarkDown',
        	'reply_markup'=>json_encode([
            	'keyboard'=>[
                [
                   ['text'=>"🔄 ساخت ربات"]
                ],
                 [
                   ['text'=>"🚀 ربات های من"],['text'=>"☢ حذف ربات"]
                ], 
                [
                   ['text'=>"📌 راهنما"],['text'=>"🔰 قوانین"],['text'=>"📢 كانال ما"]
                ]
                
            	],
            	'resize_keyboard'=>true
       		])
    		]));
}
elseif ($step == 'delete') {
$botun = $txtmsg ;
if (file_exists("bots/".$botun."/index.php")) {

$src = file_get_contents("bots/".$botun."/index.php");

if (strpos($src , $from_id) !== false ) {
save("data/$from_id/step.txt","none");
unlink("bots/".$botun."/index.php");
var_dump(makereq('sendMessage',[
        	'chat_id'=>$update->message->chat->id,
        	'text'=>"✅ ربات شما با موفقیت حذف شده است!
⚠️ یک ربات جدید بسازید.",
		'parse_mode'=>'MarkDown',
        	'reply_markup'=>json_encode([
            	'keyboard'=>[
                [
                   ['text'=>"🔄 ساخت ربات"],['text'=>"🔙 برگشت"]
                ]
                
            	],
            	'resize_keyboard'=>true
       		])
    		]));
}
else {
SendMessage($chat_id,"⛔️ خطا!
⚠️ شما نمیتوانید این ربات را پاک کنید.");
}
}
else {
SendMessage($chat_id,"⛔️ یافت نشد!");
}
}
elseif ($step == 'create bot') {
$token = $textmessage ;

			$userbot = json_decode(file_get_contents('https://api.telegram.org/bot'.$token .'/getme'));
			//==================
			function objectToArrays( $object ) {
				if( !is_object( $object ) && !is_array( $object ) )
				{
				return $object;
				}
				if( is_object( $object ) )
				{
				$object = get_object_vars( $object );
				}
			return array_map( "objectToArrays", $object );
			}

	$resultb = objectToArrays($userbot);
	$un = $resultb["result"]["username"];
	$ok = $resultb["ok"];
		if($ok != 1) {
			//Token Not True
			SendMessage($chat_id,"⛔️ توکن نامعتبر!");
		}
		else
		{
		SendMessage($chat_id,"♻️ در حال ساخت ربات شما ...");
		if (file_exists("bots/$un/index.php")) {
		$source = file_get_contents("bot/index.php");
		$source = str_replace("[*BOTTOKEN*]",$token,$source);
		$source = str_replace("238773538",$from_id,$source);
		save("bots/$un/index.php",$source);	
		file_get_contents("https://api.telegram.org/bot".$token."/setwebhook?url=https://pmresan-shervin921.rhcloud.com/pmresan/bots/$un/index.php");

var_dump(makereq('sendMessage',[
        	'chat_id'=>$update->message->chat->id,
        	'text'=>"  🔸ربات شما با موفقيت ساخته شد.
🔷 براي مديريت ربات خود به ربات خود رفته و استارت كنيد . 

[🔃برای ورود به ربات خود کلیک کنید .](https://telegram.me/$un)",
		'parse_mode'=>'MarkDown',
        	'reply_markup'=>json_encode([
            	'keyboard'=>[
                [
                 ['text'=>"🔙 برگشت"]
                ]
                
            	],
            	'resize_keyboard'=>true
       		])
    		]));
		}
		else {
		save("data/$from_id/tedad.txt","1");
		save("data/$from_id/step.txt","none");
		save("data/$from_id/bots.txt","$un");
		
		mkdir("bots/$un");
		mkdir("bots/$un/data");
		mkdir("bots/$un/data/btn");
		mkdir("bots/$un/data/words");
		mkdir("bots/$un/data/profile");
		mkdir("bots/$un/data/setting");
		
		save("bots/$un/data/blocklist.txt","");
		save("bots/$un/data/last_word.txt","");
		save("bots/$un/data/pmsend_txt.txt","Message Sent!");
		save("bots/$un/data/start_txt.txt","Hello World!");
		save("bots/$un/data/forward_id.txt","");
		save("bots/$un/data/users.txt","$from_id\n");
		mkdir("bots/$un/data/$from_id");
		save("bots/$un/data/$from_id/type.txt","admin");
		save("bots/$un/data/$from_id/step.txt","none");
		
		save("bots/$un/data/btn/btn1_name","");
		save("bots/$un/data/btn/btn2_name","");
		save("bots/$un/data/btn/btn3_name","");
		save("bots/$un/data/btn/btn4_name","");
		
		save("bots/$un/data/btn/btn1_post","");
		save("bots/$un/data/btn/btn2_post","");
		save("bots/$un/data/btn/btn3_post","");
		save("bots/$un/data/btn/btn4_post","");
	
		save("bots/$un/data/setting/sticker.txt","✅");
		save("bots/$un/data/setting/video.txt","✅");
		save("bots/$un/data/setting/voice.txt","✅");
		save("bots/$un/data/setting/file.txt","✅");
		save("bots/$un/data/setting/photo.txt","✅");
		save("bots/$un/data/setting/music.txt","✅");
		save("bots/$un/data/setting/forward.txt","✅");
		save("bots/$un/data/setting/joingp.txt","✅");
		
		$source = file_get_contents("bot/index.php");
		$source = str_replace("[*BOTTOKEN*]",$token,$source);
		$source = str_replace("275387751",$from_id,$source);
		save("bots/$un/index.php",$source);	
		file_get_contents("http://api.pwrtelegram.xyz/bot".$token."/setwebhook?url=http://climaxit.net/sample/bots/$un/index.php");

var_dump(makereq('sendMessage',[
        	'chat_id'=>$update->message->chat->id,
        	'text'=>"ربات شما با موفقيت ساخته شد.
🔷 براي مديريت ربات خود به ربات خود رفته و استارت كنيد .

[🔃برای ورود به ربات خود کلیک کنید .](https://telegram.me/$un)",
		'parse_mode'=>'MarkDown',
        	'reply_markup'=>json_encode([
            	'keyboard'=>[
                [
                   ['text'=>"🔙 برگشت"]
                ]
                
            	],
            	'resize_keyboard'=>true
       		])
    		]));
		}
}
}
elseif (strpos($textmessage, "/setvip") !== false) {
$botun = str_replace("/setvip ","",$textmessage);
SendMessage($chat_id,"$textmessage");
/*$src = file_get_contents("bots/$botun/index.php");
$nsrc = str_replace("**free**","gold",$src);
save("data/$botun/index.php",$nsrc);
SendMessage($chat_id,"Updated!");*/
}
elseif (strpos($textmessage , "/toall") !== false ) {
if ($from_id == $admin) {
$text = str_replace("/toall","",$textmessage);
$fp = fopen( "data/users.txt", 'r');
exit;
}
else {
SendMessage($chat_id,"⛔️ شما ادمین نیستید.");
}
}
elseif($textmessage == '🚀 ربات های من')
{
$botname = file_get_contents("data/$from_id/bots.txt");
if ($botname == "") {
SendMessage($chat_id,"⛔️ شما هنوز ربات نساخته اید.");
return;
}
 	var_dump(makereq('sendMessage',[
	'chat_id'=>$update->message->chat->id,
	'text'=>"💠 لیست ربات های شما :",
	'parse_mode'=>'MarkDown',
	'reply_markup'=>json_encode([
	'inline_keyboard'=>[
	[
        ['text'=>"👉 @".$botname,'url'=>"https://telegram.me/".$botname]
	]
	]
	])
	]));
}
elseif ($textmessage == '/tutorial_token') {
var_dump(makereq('sendMessage',[
        	'chat_id'=>$update->message->chat->id,
        	'text'=>"💠 آموزش ساخت روبات :
[👆 کلیک برای دیدن آموزش.](https://telegram.me/MuteTeam/2576)
",
		'parse_mode'=>'MarkDown',
        	'reply_markup'=>json_encode([
            	'resize_keyboard'=>true
       		])
    		]));
}
	elseif ($textmessage == '/sta' && $from_id == $admin) {
	$usercount = -1;
	$fp = fopen( "data/users.txt", 'r');
	while( !feof( $fp)) {
    		fgets( $fp);
    		$usercount ++;
	}
	fclose( $fp);
	SendMessage($chat_id,"`👤 اعضای ربات`: `".$usercount."`");
	}
elseif ($textmessage == '🔰 قوانین') {
var_dump(makereq('sendMessage',[
        	'chat_id'=>$update->message->chat->id,
        	'text'=>"1⃣ اطلاعات ثبت شده در ربات های ساخته شده توسط مسیج رسان از قبیل اطلاعات پروفایل نزد مدیران پیوی رسان محفوظ است و در اختیار اشخاص حقیقی یا حقوقی قرار نخواهد گرفت.

2⃣ ربات هایی که اقدام به انشار تصاویر یا مطالب مستهجن کنند و یا به مقامات ایران ، ادیان و اقوام و نژادها توهین کنند مسدود خواهند شد.

3⃣ ایجاد ربات با عنوان های مبتذل و خارج از عرف جامعه که برای جذب آمار و فروش محصولات غیر متعارف باشند ممنوع می باشد و در صورت مشاهده ربات مورد نظر حذف و مسدود میشود.

4⃣ مسئولیت پیام های رد و بدل شده در هر ربات با مدیر آن ربات میباشد و مسیج رسان هیچ گونه مسئولیتی قبول نمیکند.

5⃣ رعایت حریم خصوصی و حقوقی افراد از جمله، عدم اهانت به شخصیت های مذهبی، سیاسی، حقیقی و حقوقی کشور و به ویژه کاربران ربات ضروری می باشد.",
		'parse_mode'=>'MarkDown',
        	'reply_markup'=>json_encode([
            	'resize_keyboard'=>true
       		])
    		]));
}
elseif ($textmessage == '/about') {
var_dump(makereq('sendMessage',[
        'chat_id'=>$update->message->chat->id,
        'text'=>"🔸تيم برنامه نويسي ميوت تيم
📢 كانال ما : @MuteTeam
👤 برنامه نويس : @MutePuker
💬 ريپورت : @MutePukerBot
",
	'parse_mode'=>'MarkDown',
        'reply_markup'=>json_encode([
            'inline_keyboard'=>[
                [
                    ['text'=>"👤 developer ",'url'=>"https://telegram.me/MuteTeam"],
                    ['text'=>"📢 كانال ما",'url'=>"https://telegram.me/PvCreators"]
                ]
            ]
        ])
    ]));
}
elseif ($textmessage == '📢 كانال ما') {
var_dump(makereq('sendMessage',[
        'chat_id'=>$update->message->chat->id,
        'text'=>"📌 براي دريافت اخرين آپديت ها كليك كنيد :",
	'parse_mode'=>'MarkDown',
        'reply_markup'=>json_encode([
            'inline_keyboard'=>[
                [
                    ['text'=>"📢 join",'url'=>"https://telegram.me/MuteStats"]
                ]
            ]
        ])
    ]));
}

elseif($textmessage == '/start')
{

if (!file_exists("data/$from_id/step.txt")) {
mkdir("data/$from_id");
save("data/$from_id/step.txt","none");
save("data/$from_id/tedad.txt","0");
save("data/$from_id/bots.txt","");
$myfile2 = fopen("data/users.txt", "a") or die("Unable to open file!");	
fwrite($myfile2, "$from_id\n");
fclose($myfile2);
}

var_dump(makereq('sendMessage',[
        	'chat_id'=>$update->message->chat->id,
        	'text'=>"سلام👋
🔸ريپورت شدي و ميخواي از اين عمل رها بشي؟ 😔
🔶 دوستات ميخوان بيان پي ويت ولي ريپورت هستن؟ 🤒

ديگه نگران نباش 😃‌ , من ميتوانم واست يك ربات پيام رسان بسازم تا دوستات بتونن بهت پيام بدن . 😇

فقط كافيه يك ربات تو @BotFather بسازي و توكنش رو به من بدي . 😊
[💡بلد نيستي ربات بسازي؟ , كليك كن](https://telegram.me/MuteTeam/2576)
📌 توكن به عنوان مثال :
123456789:hiAjPlaUndhkQplmzueTbpwsH

بعد من به شما پيغامي ميدم كه ربات شما ساخته شد , بعد اون به رباتتون رفته دستور /start را ارسال كنيد ❤️👍

🔹 ربات اول : رايگان
🔷 ربات دوم : 2000 تومان

[به من امتياز بده ⭐️](https://telegram.me/storebot?start=PmResanTGbot)

[کانال پشتیبانی 📢](https://telegram.me/joinchat/DjtlIj6qRnex6UF4Ft7OeA)",
		'parse_mode'=>'MarkDown',
        	'reply_markup'=>json_encode([
            	'keyboard'=>[
                [
                   ['text'=>"🔄 ساخت ربات"]
                ],
                 [
                   ['text'=>"🚀 ربات های من"],['text'=>"☢ حذف ربات"]
                ],
                [
                   ['text'=>"⚠️ راهنما"],['text'=>"🔰 قوانین"],['text'=>"📢 كانال ما"]
                ]
                
            	],
            	'resize_keyboard'=>true
       		])
    		]));
}
elseif ($textmessage == '⚠️ راهنما') {
var_dump(makereq('sendMessage',[
        	'chat_id'=>$update->message->chat->id,
        	'text'=>"💠 آموزش ساخت ربات :
➖➖➖
1⃣ ابتدا وارد ربات زیر شوید
@BotFather
2⃣ دستور /newbot را ارسال کنید.
از شما نامی برای ربات میخواهد. انرا ارسال نمایید.
3⃣ حال شما باید آیدی وارد کنید.
ایدی که وارد میکنید اخر ان باید عبارت
Bot
وجود داشته باشد.
4⃣ یک توکن به شما میدهد مانند:
123456:asdjhasjkdhjaksdhjasdlasjkdh
5⃣ وارد ربات ما یعنی @PmResanTGBot شوید و سپس دکمه 🔄 ساخت ربات را انتخاب کنید.
و توکن دریافتی را ارسال نمایید تا ربات شما نصب شود.
",
		'parse_mode'=>'MarkDown',
        	'reply_markup'=>json_encode([

            	'resize_keyboard'=>true
       		])
    		]));
}
elseif ($textmessage == '☢ حذف ربات') {
if (file_exists("data/$from_id/step.txt")) {

}
$botname = file_get_contents("data/$from_id/bots.txt");
if ($botname == "") {
SendMessage($chat_id,"⛔️ شما هنوز هیچ رباتی نساخته اید!");

}
else {
//save("data/$from_id/step.txt","delete");


 	var_dump(makereq('sendMessage',[
	'chat_id'=>$update->message->chat->id,
	'text'=>"💠 یکی از ربات های خود را انتخاب کنید :",
	'parse_mode'=>'MarkDown',
	'reply_markup'=>json_encode([
	'inline_keyboard'=>[
	[
	['text'=>"👉 @".$botname,'callback_data'=>"del ".$botname]
	]
	]
	])
	]));

/*
var_dump(makereq('sendMessage',[
        	'chat_id'=>$update->message->chat->id,
        	'text'=>"💠 یکی از ربات های خود را جهت پاک کردن انتخاب کنید : ",
		'parse_mode'=>'MarkDown',
        	'reply_markup'=>json_encode([
            	'keyboard'=>[
            	[
            	['text'=>$botname]
            	],
                [
                   ['text'=>"🔙 برگشت"]
                ]
                
            	],
            	'resize_keyboard'=>true
       		])
    		])); */
}
}
elseif ($textmessage == '🔄 ساخت ربات') {

$tedad = file_get_contents("data/$from_id/tedad.txt");
if ($tedad >= 1) {
SendMessage($chat_id,"⛔️ تعداد ربات های ساخته شده شما زیاد است!
⚠️ اول باید یک ربات را پاک کنید!");
return;
}
save("data/$from_id/step.txt","create bot");
var_dump(makereq('sendMessage',[
        	'chat_id'=>$update->message->chat->id,
        	'text'=>"⚙ به بخش ساخت ربات خوش آمدید

جهت ساخت ربات توکن دریافتی از بات فادر را ارسال كنيد : ",
		'parse_mode'=>'MarkDown',
        	'reply_markup'=>json_encode([
            	'keyboard'=>[
                [
                   ['text'=>"🔙 برگشت"]
                ]
                
            	],
            	'resize_keyboard'=>true
       		])
    		]));
}

else
{
SendMessage($chat_id,"⛔️` Command Not Found!`
⛔️ دستور مورد نظر یافت نشد!");
}
?>
