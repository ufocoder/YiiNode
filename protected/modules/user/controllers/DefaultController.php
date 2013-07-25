<?php

class DefaultController extends Controller {

    public function actionIndex() {
        $this->forward('/user/profile');
    }
}