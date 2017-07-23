<style type="text/css">
   .say_sabit{
      background-color:green;
      display: inline-block;
      width:50px;
      text-align: center;

   }

   .say_aktif{

      background-color:gray;
      display: inline-block;
      border:1px solid black;
      width:20px;
      text-align: center;
   }
   .say_a{
      background-color:gray;
      display: inline-block;
      width:20px;
      text-align: center;
      border:1px solid black;
   }
   .say_a:hover{
      background-color:#23231f;

   }
   a{
      text-decoration: none;
      color:white;
   }


</style>
<?php 

//----------------------------------------- KODLARI KENDİ BİLGİLERİNİZ İLE DÜZELTİN ------------------------------------------------------------

try{ 

   $db = new PDO("mysql:host=localhost;dbname=dasabaseismi;charset=utf8", "root",""); //veri tabını baglantısı

}catch(PDOException $e){

   echo $e->getMessage(); 

}

$Sayfa   = @ceil($_GET['sayfa']); //5,2 girilirse eğer get o zaman onu tam sayı yapar yanı 5 yapıyoruz bu kod ile
if ($Sayfa < 1) { $Sayfa = 1;} //eğer get değeri yerine girilen sayi 1 den küçükse sayfa değerini 1 yapıyoruz yani 1. sayfaya atıyoruz
$Say   = $db->query("select * from icerik order by icerik_id DESC"); //makale sayısını çekiyoruz

$ToplamVeri   = $Say->rowCount(); //makale sayısını saydırıyoruz

$Limit	= 3; //bir sayfada kaç içerik çıkacağını belirtiyoruz. 

$Sayfa_Sayisi	= ceil($ToplamVeri/$Limit); //toplam veri ile limiti bölerek her toplam sayfa sayısını buluyoruz

if($Sayfa > $Sayfa_Sayisi){$Sayfa = $Sayfa_Sayisi;} //eğer yazılan sayı büyükse eğer toplam sayfa sayısından en son sayfaya atıyoruz kullanıcıyı

$Goster   = $Sayfa * $Limit - $Limit; // sayfa= 2 olsun limit=3 olsun 2*3=6 6-3=3 buranın değeri 2. sayfada 3'dür 3-4-5-6... sayfalarda da aynı işlem yapılıp değer bulunur

$GorunenSayfa   = 5; //altta kaç tane sayfa sayısı görüneceğini belirtiyoruz.


$Makale	= $db->query("select * from icerik order by icerik_id DESC limit $Goster,$Limit"); //yukarda göstere attıgımız değer diyelim ki 3 o zaman 3.'id den başlayarak limit kadar veri ceker.

$MakaleAl = $Makale->fetchAll(PDO::FETCH_ASSOC);

?>

<div id="Makale">

   <?php foreach($MakaleAl as $MakaleCek){?>

   <div class="Makale">

      <h1><?=$MakaleCek["icerik_ad"]?></h1> 

      <span><?=$MakaleCek["icerik_detay"]?></span>

   </div>

   <?php } ?>

   <?php if($Sayfa > 1){?>

   <span class="say_sabit"><a href="index.php?sayfa=1">İlk</a></span> <!--1. Sayfaya gider-->

   <div class="say_sabit"><a href="index.php?sayfa=<?=$Sayfa - 1?>">Önceki</a></div> <!--Bir Önceki Sayfaya Gitmek İçin Sayfa Değerini 1 eksiltiyoruz-->

   <?php } ?>

   <?php 

    for($i = $Sayfa - $GorunenSayfa; $i < $Sayfa + $GorunenSayfa +1; $i++){ // i kaç ise o sayıdan başlar 1-2-3-4-5 yazmaya. mesela sayfa 7deyiz 7 - 5 = 2'dir 2 sayfadan sonra sayfalamaya başlar yani 2-3-4-5-6-7 gibi bu aynı mantıkla devam eder.


      if($i > 0 and $i <= $Sayfa_Sayisi){

         if($i == $Sayfa){

            echo '<span class="say_aktif">'.$i.'</span>'; //eğer i ile sayfa değerleri aynıysa o zaman onu aktif css'si ekle

         }else{

            echo '<a class="say_a" href="index.php?sayfa='.$i.'">'.$i.'</a>'; //eğer aynı değilse normal listele

         }

      }

   }
   ?>
   <?php if($Sayfa != $Sayfa_Sayisi){?>

   <div class="say_sabit"><a href="index.php?sayfa=<?=$Sayfa + 1?>">Sonraki</a></div><!--Bir Sonra ki Sayfaya Gitmek için sayfa değerini 1 artırıyoruz.-->

   <div class="say_sabit"><a href="index.php?sayfa=<?=$Sayfa_Sayisi?>">Son</a></div><!--Buldugumuz Toplam Sayfa Sayısını buraya cekiyoruz tıklandıgında en son sayfaya gider-->

   <?php } ?>
