<?php
    $baseUrl = Yii::app()->request->baseUrl;
?>

  <footer id="footer">
    <div class="container">
    <div class="greyline"></div>
    &copy; <?php echo date('Y'); ?> Vegaeda.ru<br/>
    <?php echo Yii::t('site', 'All Rights Reserved.'); ?><br/>
    </div>
  </footer>
  <div class="backToTop"><a href="#top"><?php Yii::t("site", "Back to top");?></a></div>
</body>
</html>