<?php
/**
 * Created by PhpStorm.
 * User: markopoikkimaki
 * Date: 2018-12-11
 * Time: 07:57
 */

namespace App\Twig;


use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{

    public function getFilters()
    {

        return [
            new TwigFilter('price', [ $this, 'priceFilter' ]),
            new TwigFilter('bingo', [ $this, 'bingoFilter' ])
        ];
    }

    public function priceFilter($number) {
        return '$'.number_format($number, 2, '.', ',');
    }

    public function bingoFilter($str){
        return "BINGO $str!";
    }




}