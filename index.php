<?php


$veri_tabani_adi="wiki";  //UTF8 Turkish-CI formantında bir veritabanı oluşturup adını buraya yazın.
$veri_tabani_kullanicisi="root"; // Tüm yetkilere sahip bir kullanıcı oluşturup buraya yazın.
$veri_tabani_sifresi=""; // Veritabanı şifrenizi yazın karmaşık ve tahmin edilmesi zor bir şey yazın.

/**
 * BİR VERİ TABANI OLUŞTURUN. 
 * VERİ TABANINA ÜÇ TANE SÜTUN OLUŞTURUN.
 * map_id --> interger
 * map_title --> text
 * map_link --> text
 * 
 */


try {

	$db=new PDO("mysql:host=localhost;dbname=$veri_tabani_adi;charset=utf8",$veri_tabani_kullanicisi,$veri_tabani_sifresi);

	// echo "Veritabanı bağlantısı başarılı";
}catch(PDOException $e){

	echo $e->getMessage();
}

echo "<hr>";
$say=0;
$sitemapsor = $db->prepare("SELECT * FROM sitemap");
$sitemapsor->execute(); 
while($sitemapcek=$sitemapsor->fetch(PDO::FETCH_ASSOC)){
$say = $say+1;
}
echo $say." adet kayıt bulundu";
echo "<hr>";

// iki sayfa kaydedildi dikkatli ol direkt kayıt yapıyor.
if(isset($_POST['listele'])){
$kaynak = $_POST['kaynak'];

$kaynak = file_get_contents($kaynak);

// $desen = '@<item>\s*<title>(.*?)</title>\s*<link>(.*?)</link>(.*?)</item>@si';
$desen = '@li><a href="(.*?)" title="(.*?)">(.*?)</a></li>@si';

preg_match_all($desen, $kaynak, $sonuc);

$dongu = count($sonuc[0]);


for($i=0;$i<$dongu;$i++){

	$link ="https://tr.wikipedia.org/".$sonuc[1][$i];
	$title = $sonuc[2][$i];

	$sitemap=$db->prepare("INSERT into sitemap set

	map_title=:map_title,
	map_link=:map_link
	");


$insert=$sitemap->execute(array(
		
	'map_title' => $title,
	'map_link' => $link
));

	//echo '<ul><li><a href="https://tr.wikipedia.org/'.$sonuc[1][$i].'">'.$sonuc[2][$i].'</a></li></ul>';
}
echo $dongu." İşlem Tamamlandı";



// $desen = '@<item>\s*<title>(.*?)</title>\s*<link>(.*?)</link>(.*?)</item>@si';
$desen2 = '@<div class="mw-allpages-nav"><a href="(.*?)" title="Özel:TümSayfalar">(.*?)</a> | <a href="(.*?)" title="Özel:TümSayfalar">(.*?)</a></div>@si';

preg_match_all($desen2, $kaynak, $sonuc2);


$yenilink = "https://tr.wikipedia.org/".$sonuc2[3][2];

}

?>

<hr>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Link Al</title>
	<link rel="icon" type="image/x-icon" href="resim/wiki.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>
<body>
<form action=""  method="POST">
<div class="row">
<div class="col-md-10"><input class="form-control" name="kaynak" value="<?php echo $yenilink; ?> " required></div><br>
<div class="col-md-2"><button class="btn btn-primary" type="submit" name="listele">Kaydet</button></div>
</div>
</div>
</form>
<hr>
<p><b>ÇALIŞMA NOTU: </b> içerik listesinin ilk sayfası olarak aşağıda ki linki yukarıda ki arama kutusuna yapıştırın. Ardından sadece KAYDET tuşuna basmanız yeter. Her seferinde bir sayfayı kaydettiğindne yüzlerce kez basmanız gerekebilir. Onun için uğraşamadım kusura bakmayın.</p>
<p>https://tr.wikipedia.org/w/index.php?title=%C3%96zel:T%C3%BCmSayfalar&from=.fail</p>
</body>
</html>