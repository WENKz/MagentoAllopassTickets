<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Couponallopass
 *
 * @author quentin
 */
class Quentingrisard_CouponAllopass_IndexController extends Mage_Core_Controller_Front_Action {

    public function resultAction()
    {
        $RECALL = $this->getRequest()->getParam('RECALL');
        $code = $RECALL;

        if (trim($RECALL) == "")
        {
            Mage::getSingleton('core/session')->addError('Votre Code n\'est pas bon');
            $this->_redirect("/acheter-avec-allopass.html");
        }
        // $RECALL contient le code d'accès
        $RECALL = urlencode($RECALL);

        // $AUTH doit contenir l'identifiant de VOTRE document
        mage::log(Mage::getStoreConfig('tab1/general/auth_field', Mage::app()->getStore()), null, "mage.log", true);
        $AUTH = urlencode(Mage::getStoreConfig('tab1/general/auth_field', Mage::app()->getStore()));

        $r = @file("http://payment.allopass.com/api/checkcode.apu?code=$RECALL&auth=$AUTH");

        // on teste la réponse du serveur

        if (substr($r[0], 0, 2) != "OK")
        {
            // Le serveur a répondu ERR ou NOK : l'accès est donc refusé
            exit(1);
        }
        $customerId = Mage::getSingleton('customer/session')->getCustomer()->getId();
        if ($this->verifCode($code) === true)
        {
            $this->saveCode($code, $customerId);

            $this->setCoupon($customerId);
            $steamid = Mage::getSingleton('customer/session')->getCustomer()->getId();
            $name = Mage::getStoreConfig('tab1/general/couponname_field', Mage::app()->getStore()) . $steamid;
            Mage::getSingleton('core/session')->addSuccess('Merci de votre dons votre coupon a été créé : ' . $name);
            $this->_redirect("/acheter-avec-allopass.html");
        }
        else
        {
            echo "nok";
            Mage::getSingleton('core/session')->addError('Votre Code a deja été utilisé');
            $this->_redirect("/acheter-avec-allopass.html");
        }

        setCookie("CODE_OK", "1", 0, "/", ".darkgames.fr", false);
    }

    private function saveCode($code, $customerId)
    {
        $data = mage::getModel("coupon/coupon")->setData(array("code" => $code, "customer_id" => $customerId, "update_time" => Mage::getModel('core/date')->date('Y-m-d H:i:s')));
        $data->save();
    }

    private function setCoupon($customerId)
    {
        $coupon = $this->getCoupon($customerId);
        if ($coupon)
        {
            $coupVal = mage::helper("couponallopass")->getCouponVal($coupon["rule_id"]);
            $this->creatCoupon($customerId, $coupVal, $coupon["rule_id"]);
        }
        else
        {
            $this->creatCoupon($customerId);
        }
    }

    private function creatCoupon($customerId, $couponVal = 0, $rule_id = null)
    {
        $steamid = Mage::getSingleton('customer/session')->getCustomer()->getSteamid();
        $name = "DARKCOUPON" . $steamid;
        Mage::helper("couponallopass")->generateRule($name, $name, $couponVal, $customerId, $rule_id);
    }

    private function updateCoupon()
    {
        
    }

    private function getCoupon($customerId)
    {
        $coupon = Mage::getModel("discount/discount")->load($customerId, "customer_id");

        return $coupon->getData();
    }

    private function verifCode($code)
    {
        // $data = Mage::getModel("coupon/coupon")->getCollection();
        $resource = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');
        $query = "SELECT count(allopass_id) as nb FROM couponallopass WHERE code = '$code'";
        $code = $readConnection->fetchAll($query);
        if ($code[0]["nb"] == 1)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

}
