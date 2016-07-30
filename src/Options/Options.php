<?php
declare(strict_types = 1);

namespace Tuck\Sort\Options;

class Options
{
    private $options = [];

    public function __construct(array $userProvidedOptions, array $supportedOptionTypes)
    {
        foreach ($userProvidedOptions as $userOption) {
            $optionType = get_class($userOption);
            if (isset($this->options[$optionType])) {
                throw new \Exception("Duplicate option of type $optionType given");
            }

            $this->options[$optionType] = $userOption;
        }

        $unallowedTypes = array_diff(array_keys($this->options), $supportedOptionTypes);
        if (count($unallowedTypes) > 0) {
            throw new \Exception("The following options are not supported with this type of sort: " . implode($unallowedTypes));
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

        return $this->options[$className] = $className::defaultOption();
    }
}