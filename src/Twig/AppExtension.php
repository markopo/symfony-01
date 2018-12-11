<?php
/**
 * Created by PhpStorm.
 * User: markopoikkimaki
 * Date: 2018-12-11
 * Time: 07:57
 */

namespace App\Twig;


use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension implements GlobalsInterface
{
    /**
     * @var string
     */
    private $locale;


    /**
     * AppExtension constructor.
     */
    public function __construct(string $locale)
    {

        $this->locale = $locale;
    }

    public function getFilters()
    {

        return [
            new TwigFilter('price', [ $this, 'priceFilter' ]),
            new TwigFilter('bingo', [ $this, 'bingoFilter' ])
        ];
    }

    public function getGlobals() {
        return [
            'locale' => $this->locale
        ];
    }



    public function priceFilter($number) {
        return '$'.number_format($number, 2, '.', ',');
    }

    public function bingoFilter($str){
        return "BINGO $str!";
    }






}