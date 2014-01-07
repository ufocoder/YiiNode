<?php
    if (!empty($sliders)):

        $baseUrl = Yii::app()->baseUrl;

        $model_class = "SliderSetting";
        $model = new $model_class;

        $settings = array(
            'sliderEnabled', 'sliderAnimSpeed', 'sliderPauseOnHover',
            'sliderPauseTime', 'sliderHeight', 'sliderWidth'
        );
        foreach($settings as $setting)
            $model->$setting = Yii::app()->getSetting($setting, 'slider', SliderSetting::values($setting, 'default'));

        if ($model->sliderEnabled = 1):
            Yii::app()->getClientScript()->registerCssFile($baseUrl."/js/bxslider/jquery.bxslider.css");
            Yii::app()->getClientScript()->registerScriptFile($baseUrl."/js/bxslider/jquery.bxslider.min.js");
            Yii::app()->getClientScript()->registerScript("nivo.slider","   $('#slider').bxSlider({
            mode: 'fade',
            auto: true,
            pause: ".$model->sliderPauseTime.",
            speed: ".$model->sliderAnimSpeed.",
            infiniteLoop: true,
            controls: false,
            tickerHover: ".$model->sliderPauseOnHover.",
        });");

?>

        <div class="slider-block">
            <ul id="slider" class="bxslider">
<?php

        foreach($sliders as $slide):
            $image = $slide['image'];
            $thumb = Yii::app()->image->thumbSrcOf($image, array(
                "resize" => array(
                    'height'=>$model->sliderHeight,
                    'width'=>$model->sliderWidth,
                    'master'=>1
                    )
                ));
?>
                    <li<?php echo !empty($slide->background)?' style="background: '.$slide->background.'"':null;?>>
                        <a href="#">
                            <img src="<?php echo $thumb; ?>" data-thumb="<?php echo $image; ?>" alt="" title="">
                        </a>
                    </li>
<?php      endforeach; ?>
                    </div>
                </div>
        </div>
<?php
        endif;
    endif;
?>