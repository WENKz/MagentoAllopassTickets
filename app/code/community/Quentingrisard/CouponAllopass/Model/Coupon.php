<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CouponAllopass
 *
 * @author quentin
 */
class Quentingrisard_CouponAllopass_Model_Coupon extends Mage_Core_Model_Abstract {

    //put your code here
    public function _construct()
    {
        parent::_construct();
        $this->_init('coupon/coupon');
    }

}
