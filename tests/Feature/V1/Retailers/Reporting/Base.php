<?php

namespace Tests\Feature\V1\Retailers\Reporting;

use Tagd\Core\Tests\Traits\NeedsConsumers;
use Tagd\Core\Tests\Traits\NeedsDatabase;
use Tagd\Core\Tests\Traits\NeedsItems;
use Tagd\Core\Tests\Traits\NeedsResales;
use Tagd\Core\Tests\Traits\NeedsResellers;
use Tagd\Core\Tests\Traits\NeedsRetailers;
use Tagd\Core\Tests\Traits\NeedsTagds;

abstract class Base extends \Tests\Feature\V1\Base
{
    use NeedsConsumers, NeedsDatabase, NeedsItems, NeedsResales, NeedsResellers, NeedsRetailers, NeedsTagds;

    /**
     * Calculate min from array
     */
    public function calculateMin(array $values): float
    {
        return min($values);
    }

    /**
     * Calculate max from array
     */
    public function calculateMax(array $values): float
    {
        return max($values);
    }

    /**
     * Calculate mean average from array
     */
    public function calculateMean(array $values): float
    {
        return array_sum($values) / count($values);
    }

    /**
     * Calculate median from array
     */
    public function calculateMedian(array $values): float
    {
        sort($values);
        $count = count($values);
        $middle = floor(($count - 1) / 2);

        if ($count % 2) {
            return $values[$middle];
        }

        return ($values[$middle] + $values[$middle + 1]) / 2;
    }

    /**
     * Calculate standard deviation from array
     */
    public function calculateStandardDeviation(array $values): float
    {
        $mean = $this->calculateMean($values);
        $count = count($values);

        $sum = 0.0;
        foreach ($values as $value) {
            $sum += pow($value - $mean, 2);
        }

        return sqrt($sum / $count);
    }

    /**
     * Calculate quantile from array
     *
     * @param  array  $values
     * @param  int  $quantile
     * @param  int  $total
     */
    // public function calculateQuantile(array $values, int $quantile, int $total = 4): float
    // {
    //     sort($values);
    //     $count = count($values);
    //     $position = ($count + 1) * $quantile / $total;

    //     if (is_int($position)) {
    //         return $values[$position - 1];
    //     }

    //     $positionFloor = floor($position);
    //     $positionCeil = ceil($position);

    //     dd($positionFloor);

    //     // dd($values);
    //     // dd($positionFloor);
    //     // dd($values);
    //     return ($values[$positionFloor - 1] + $values[$positionCeil - 1]) / 2;
    // }

    public function calculateQuantile(array $list, int $number, int $count = 4): float
    {
        $quantile = (1 / $count * $number);
        // $quantile = min(100, max(0, $quantile));

        $array = array_values($list);

        // sort($array);
        $index = ($quantile / 100) * (count($list) - 1);
        $fractionPart = $index - floor($index);
        $intPart = floor($index);

        $percentile = $list[$intPart];
        $percentile += ($fractionPart > 0) ? $fractionPart * ($list[$intPart + 1] - $array[$intPart]) : 0;

        return $percentile;

        // switch ($quantile) {
        //     case 1:
        //         $quartile = 0.25;
        //         break;
        //     case 2:
        //         $quartile = 0.5;
        //         break;
        //     case 3:
        //         $quartile = 0.75;
        //         break;
        //     case 4:
        //         $quartile = 1;
        //         break;
        //     default:
        //         throw new \InvalidArgumentException('Quantile must be between 1 and 4');
        // }

        // // quartile position is number in array + 1 multiplied by the quartile i.e. 0.25, 0.5, 0.75
        // $pos = (count($values) + 1) * $quartile;

        // // if the position is a whole number
        // // return that number as the quarile placing
        // if ( fmod($pos, 1) == 0)
        // {
        //     return $this->safePosFromArray($values, $pos);
        // }
        // else
        // {
        //     // get the decimal i.e. 5.25 = .25
        //     $fraction = $pos - floor($pos);

        //     // get the values in the array before and after position
        //     $lower_pos = floor($pos)-1;
        //     $upper_pos = ceil($pos)-1;

        //     $lower_num = $this->safePosFromArray($values, $lower_pos);
        //     $upper_num = $this->safePosFromArray($values, $upper_pos);

        //     // get the difference between the two
        //     $difference = $upper_num - $lower_num;

        //     // the quartile value is then the difference multipled by the decimal
        //     // add to the lower number
        //     return $lower_num + ($difference * $fraction);
        // }
    }

    // /**
    //  * Get the position from an array safely
    //  *
    //  * @param array $values
    //  * @param int $pos
    //  * @return mixed
    //  */
    // protected function safePosFromArray(array $values, int $pos): mixed
    // {
    //     if ($pos < 0) { $pos = 0; }
    //     if ($pos >= count($values)) { $pos = count($values) - 1; }

    //     return $values[$pos];
    // }

    /**
     * Convert array to json and back to array
     * This is to reproduce same behaviour as the API where x.0 values are converted to x
     *
     * @return object
     */
    protected function convertArray(array $values): array
    {
        return json_decode(json_encode($values), true);
    }
}
