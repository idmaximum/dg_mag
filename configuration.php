<?php
$_Config_absolute_path = dirname(__FILE__);

$_Config_root = 'http://localhost/dg_magazine';
$_Config_live_site = 'http://idmaxdev.com/dg_magazine_pr';
$languageMulti = "1";//1=single language ,2= th,en

#Content Config
$_Config_sitename = 'DG Magazine แบ่งปันไอเดียตกแต่งบ้าน เก็บภาพสวยสไตล์คุณ ';
$_Config_keyword = 'ตกแต่งบ้าน,เทคนิคตกแต่งบ้าน,ตกแต่งสวนสวย,อุปกรณ์ตกแต่ง';
$_Config_description = 'DG magazine เว็บไซต์ของคนรักบ้าน ตกแต่งบ้าน เปิดโอกาสให้คุณ Share ภาพถ่ายฝีมือการตกแต่งของคุณเอง แบ่งปันให้สมาชิกท่านอื่นได้นำไปใช้ หรือเกิดไอเดียการทำบ้านให้สวยให้น่าอยู่ และคุณยังสามารถเก็บภาพประทับใจของคนอื่นไว้ใน Album ส่วนตัว เพื่อเก็บไว้ดูทีหลังก็ได้';

$_Config_domain = "http://localhost/dg_magazine";

$languageMulti = '1';
 
$_Config_jquery_version = 'jquery';
 
#DataBase Table Config
$_Confgi_DB_prefix = "sbd_";
$_Config_table["profile"] = $_Confgi_DB_prefix.'profile';//Profile;
 
$_Config_table["site"] = $_Confgi_DB_prefix.'site';//Site;
  
$_Config_table["item_category"] = 'item_category';//item_category
$_Config_table["item_subcategory"] = 'item_subcategory';//item_subcategory
$_Config_table["item"] = 'item';//Main
$_Config_table["itemimage"] = 'item_image';//More picture

$_Config_table["news"] = 'news';//news

$_Config_table["buynow"] = 'buynow';//news

$_Config_table["frontpage"] = 'picfrontpage';
$_Config_table["banner"] = 'banner';
$_Config_table["banner_category"] = 'banner_category'; 
$_Config_table["board_category"] = 'board_category'; 

$_Config_table["comment"] = 'comment'; 

$_Config_table["pin_article"] = 'pin_article';  

$_Config_table["contact"] = $_Confgi_DB_prefix.'contact';//contact  
$_Config_table["member"] = 'member';

$_Config_table["click"] = 'click';
$_Config_table["like"] = 'like_item';
$_Config_table["view"] = 'view';
$_Config_table["mail_subscribe"] =  'newsletter';
//setlocale (LC_TIME, $_Config_locale);
?>