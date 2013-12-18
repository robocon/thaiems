<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>ตัวอย่างการใช้งาน - คำนวณค่าส่ง EMS จากน้ำหนักสินค้า</title>
</head>
<body>
    <form action="index.php" method="post">
        <label for="weight">น้ำหนักสินค้า</label>
        <input type="text" name="weight" id="weight" value="<?php echo empty($_POST['weight']) ? '1250' : $_POST['weight'] ; ?>">
        <input type="submit" value="Submit">
    </form>
    <?php
    if(!empty($_POST['weight'])){
        include 'rateth.php';

        RateTH::$_feed = 'http://localhost/thaiems/thaiems/example/thaiems.php';

        $rate = new RateTH();
        $rate->set_weight($_POST['weight']);

        if(!$rate->getError()){
            ?>
            <p><b>EMS ในประเทศ</b>: <?php echo $rate->get_price()?>บาท</p>
            <p><b>พัสดุไปรษณีย์</b>: <?php echo $rate->get_price(1)?>บาท</p>
            <?php
        }
    }
    ?>
    <a href="http://www.thailandpost.com/rate.php" target="_blank">ตรวจสอบอัตราค่าบริการ จากไปรณีย์ไทย</a>
</body>
</html>