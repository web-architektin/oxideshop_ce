<?php
/**
 * Price enter mode:  brutto
 * Price view mode: brutto
 * Product count: count of used products
 * VAT info: count of used vat's (list)
 * Currency rate: 1.0 (change if needed)
 * Discounts: count
 *  1. basket 10 %
 * Wrapping:  -
 * Gift cart: -;
 * Costs VAT caclulation rule: proportiona
 * 5 products with different vat. Absolute discount. Bruto-Bruto mode.
 */
 
$aData = array(
    // Product
    'articles' => array (
        0 => array (
            // oxarticles db fields
            'oxid'                     => 1001,
            'oxprice'                  => 1002.55,
            'oxvat'                    => 19,
            // Amount in basket
            'amount'                   => 2,
			'scaleprices' => array(
				0 => array (
                        'oxaddabs'     => 1002.55,
                        'oxamount'     => 1,
                        'oxamountto'   => 5,
                        'oxartid'      => 1001
                ),
				1 => array (
                        'oxaddabs'     => 1089.65,
                        'oxamount'     => 6,
                        'oxamountto'   => 10,
                        'oxartid'      => 1001
                ),
			),
        ),
        1 => array (
          // oxarticles db fields
            'oxid'                     => 1002,
            'oxprice'                  => 11.56,
            'oxvat'                    => 13,
            // Amount in basket
            'amount'                   => 2,
			'scaleprices' => array(
				0 => array (
                        'oxaddabs'     => 11.56,
                        'oxamount'     => 1,
                        'oxamountto'   => 5,
                        'oxartid'      => 1002
                ),
				1 => array (
                        'oxaddabs'     => 16.55,
                        'oxamount'     => 6,
                        'oxamountto'   => 10,
                        'oxartid'      => 1002
                ),
			),

        ),
         2 => array (
          // oxarticles db fields
            'oxid'                     => 1003,
            'oxprice'                  => 1326.89,
            'oxvat'                    => 3,
            // Amount in basket
            'amount'                   => 6,
			'scaleprices' => array(
				0 => array (
                        'oxaddabs'     => 1325.45,
                        'oxamount'     => 1,
                        'oxamountto'   => 5,
                        'oxartid'      => 1003
                ),
				1 => array (
                        'oxaddabs'     => 1326.89,
                        'oxamount'     => 6,
                        'oxamountto'   => 10,
                        'oxartid'      => 1003
                ),
			),

        ),
         3 => array (
          // oxarticles db fields
            'oxid'                     => 1004,
            'oxprice'                  => 6.66,
            'oxvat'                    => 17,
            // Amount in basket
            'amount'                   => 6,
			'scaleprices' => array(
				0 => array (
                        'oxaddabs'     => 5.65,
                        'oxamount'     => 1,
                        'oxamountto'   => 5,
                        'oxartid'      => 1004
                ),
				1 => array (
                        'oxaddabs'     => 5.69,
                        'oxamount'     => 6,
                        'oxamountto'   => 10,
                        'oxartid'      => 1004
                ),
			),

        ),
         4 => array (
          // oxarticles db fields
            'oxid'                     => 1005,
            'oxprice'                  => 0.66,
            'oxvat'                    => 33,
            // Amount in basket
            'amount'                   => 6,
			'scaleprices' => array(
				0 => array (
                        'oxaddabs'     => 0.55,
                        'oxamount'     => 1,
                        'oxamountto'   => 5,
                        'oxartid'      => 1005
                ),
				1 => array (
                        'oxaddabs'     => 0.66,
                        'oxamount'     => 6,
                        'oxamountto'   => 10,
                        'oxartid'      => 1005
                ),
			),

        ),
    ),
    // Discounts
    'discounts' => array (
        // oxdiscount DB fields
        0 => array (
            // ID needed for expectation later on, specify meaningful name
            'oxid'         => 'absdiscount',
            'oxaddsum'     => 125.55,
            'oxaddsumtype' => 'abs',
            'oxamount' => 1,
            'oxamountto' => 99999,
            'oxactive' => 1,
        ),
         
    ),
    // Additional costs
    'costs' => array(
        // Delivery
        'delivery' => array(
            0 => array(
                // oxdelivery DB fields
                'oxactive' => 1,
                'oxaddsum' => 3.14,
                'oxaddsumtype' => 'abs',
                'oxdeltype' => 'p',
                'oxfinalize' => 1,
                'oxparamend' => 99999,
            ),
        ),
        // Payment
        'payment' => array(
            0 => array(
                // oxpayments DB fields
                'oxaddsum' => 7.59,
                'oxaddsumtype' => 'abs',
                'oxfromamount' => 0,
                'oxtoamount' => 1000000,
                'oxchecked' => 1,
            ),
        ),
    ),
	
    // TEST EXPECTATIONS
    'expected' => array (
        // Article expected prices: ARTICLE ID => ( Unit price, Total Price )
        'articles' => array (
            1001 => array ( '1.002,55', '2.005,10' ),
            1002 => array ( '11,56', '23,12' ),
            1003 => array ( '1.326,89', '7.961,34' ),
            1004 => array ( '6,66', '39,96' ),
            1005 => array ( '0,66', '3,96' ),
        ),
        // Expectations of other totals
        'totals' => array (
            // Total BRUTTO
            'totalBrutto' => '10.033,48',
            // Total NETTO
            'totalNetto'  => '9.353,48',
            // Total VAT amount: vat% => total cost
            'vats' => array (
                19 => '316,14',
                13 => '2,63',
                3  => '228,98',
                17 => '5,73',
                33 => '0,97',
            ),
            // Total discount amounts: discount id => total cost
            'discounts' => array (
                // Expectation for special discount with specified ID
                'absdiscount' => '125,55',
            ),
            // Total delivery amounts
            'delivery' => array(
			    'brutto' => '3,14',
                'netto' => '3,05',
                'vat' => '0,09'
            ),
            // Total payment amounts
            'payment' => array(
			   'brutto' => '7,59',
                'netto' => '7,37',
                'vat' => '0,22'
            ),
            // GRAND TOTAL
            'grandTotal'  => '9.918,66'
        ),
    ),
    // Test case options
    'options' => array (
        // Configs (real named)
        'config' => array(
            'blEnterNetPrice' => false,
            'blShowNetPrice' => false,
            'blShowVATForPayCharge' => true,
            'blShowVATForDelivery' => true,
        ),
        // Other options
        'activeCurrencyRate' => 1,
    ),
);