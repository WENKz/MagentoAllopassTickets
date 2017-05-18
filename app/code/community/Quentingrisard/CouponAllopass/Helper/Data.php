<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Data
 *
 * @author quentin
 */
class Quentingrisard_CouponAllopass_Helper_Data extends Mage_Core_Helper_Abstract{

    public function generateRule($name = null, $coupon_code = null, $discount = 0, $customerId, $rule_id = "")
    {
        if ($name != null && $coupon_code != null)
        {
            $rule = Mage::getModel('salesrule/rule');
            if (!empty($rule_id))
            {
                $rule->load($rule_id);

                $rule->setId($rule_id);
            }
            $customer_groups = array(0, 1, 2, 3);
            $rule->setName($name)
                    ->setDescription($name)
                    ->setFromDate('')
                    ->setCouponType(2)
                    ->setCouponCode($coupon_code)
                    ->setUsesPerCustomer('')
                    ->setCustomerGroupIds($customer_groups) //an array of customer grou pids
                    ->setIsActive(1)
                    ->setConditionsSerialized('')
                    ->setActionsSerialized('')
                    ->setStopRulesProcessing(0)
                    ->setIsAdvanced(1)
                    ->setProductIds('')
                    ->setSortOrder(0)
                    ->setSimpleAction('cart_fixed')
                    ->setDiscountAmount($discount + 1)
                    ->setDiscountQty(null)
                    ->setDiscountStep(0)
                    ->setSimpleFreeShipping('0')
                    ->setApplyToShipping('0')
                    ->setIsRss(0)
                    ->setWebsiteIds(array(1));

//            $item_found = Mage::getModel('salesrule/rule_condition_product_found')
//                    ->setType('salesrule/rule_condition_product_found')
//                    ->setValue(1) // 1 == FOUND
//                    ->setAggregator('all'); // match ALL conditions
//            $rule->getConditions()->addCondition($item_found);
//            $conditions = Mage::getModel('salesrule/rule_condition_product')
//                    ->setType('salesrule/rule_condition_product')
//                    ->setAttribute('sku')
//                    ->setOperator('==')
//                    ->setValue($sku);
//            $item_found->addCondition($conditions);
//
//            $actions = Mage::getModel('salesrule/rule_condition_product')
//                    ->setType('salesrule/rule_condition_product')
//                    ->setAttribute('sku')
//                    ->setOperator('==')
//                    ->setValue($sku);
//            $rule->getActions()->addCondition($actions);
            $rule->save();
            $save = mage::getmodel('discount/discount')->setData(array("customer_id" => Mage::getSingleton('customer/session')->getCustomer()->getId(), "rule_id" => $rule->getId()));
//                    ->setRuleId($rule->getId())
//                    ->setCustomerId(Mage::getSingleton('customer/session')->getCustomer()->getEntityId());
            $save->save();
            mage::log($rule->getId(), null, "rule.log", true);
        }
    }

    public function getCouponVal($rule_id)
    {
        $coup = mage::getModel('salesrule/rule')->load($rule_id)->getData();
        return $coup["discount_amount"];
    }

}
