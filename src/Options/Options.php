<?php
declare(strict_types = 1);

namespace Tuck\Sort\Options;

use Tuck\Sort\Options\Exception\NotAnOption;
use Tuck\Sort\Options\Exception\OptionSetMultipleTimes;
use Tuck\Sort\Options\Exception\UnsupportedOption;

class Options
{
    private $options = [];

    public function __construct(array $userProvidedOptions, array $supportedUserOptionTypes)
    {
        foreach ($userProvidedOptions as $userOption) {
            if (!$userOption instanceof Option) {
                throw NotAnOption::givenItem($userOption);
            }

            $optionType = get_class($userOption);
            if (isset($this->options[$optionType])) {
                throw OptionSetMultipleTimes::ofType($optionType);
            }

            $this->options[$optionType] = $userOption;
        }

        $unallowedTypes = array_diff(array_keys($this->options), $supportedUserOptionTypes);
        if (count($unallowedTypes) > 0) {
            throw UnsupportedOption::multipleTypes($unallowedTypes);
        }
    }

    public function asFlags()
    {
        return array_reduce(
            $this->options,
            function ($currentFlags, Option $option) {
                return $option->buildFlags($currentFlags);
            },
            SORT_REGULAR
        );
    }

    /**
     * @return Casing
     */
    public function casing()
    {
        return $this->getOption(Casing::class);
    }

    /**
     * @return Keys
     */
    public function keys()
    {
        return $this->getOption(Keys::class);
    }

    /**
     * @return Order
     */
    public function order()
    {
        return $this->getOption(Order::class);
    }

    /**
     * @param string $className
     * @return Option
     */
    private function getOption($className)
    {
        if (isset($this->options[$className])) {
            return $this->options[$className];
        }

        return $this->options[$className] = $className::defaultSetting();
    }
}