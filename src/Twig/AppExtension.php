<?php
    namespace App\Twig;

    use Twig\Extension\AbstractExtension;
    use Twig\TwigFilter;
    
    class AppExtension extends AbstractExtension
    {
        public function getFilters()
        {
            return [
                new TwigFilter('price', [$this, 'formatPrice']),
                new TwigFilter('date_form', [$this, 'formatDate']),
            ];
        }
    
        public function formatPrice($number, $decimals = 0, $decPoint = '.', $thousandsSep = ',')
        {
            $price = number_format($number, $decimals, $decPoint, $thousandsSep);
            $price = $price.'₮';
    
            return $price;
        }

        public function formatDate($date)
        {
            $price = date_format($date, 'Y/m/d');
    
            return $price;
        }
    }