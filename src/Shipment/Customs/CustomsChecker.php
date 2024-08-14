<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Shipment\Customs;

class CustomsChecker
{
    /**
     * @date Valid from 01/01/2021
     * @source https://ec.europa.eu/taxation_customs/business/vat/eu-vat-rules-topic/territorial-status-eu-countries-certain-territories_en
     */
    public const EU_COUNTRIES = [
        'AT',
        'BE',
        'BG',
        'HR',
        'CY',
        'CZ',
        'DE',
        'DK',
        'EE',
        'ES',
        'FI',
        'FR',
        'GR',
        'HU',
        'IE',
        'IT',
        'LV',
        'LT',
        'LU',
        'MT',
        'MC',
        'NL',
        'PL',
        'PT',
        'RO',
        'SK',
        'SI',
        'SE',
    ];

    public const CUSTOMS_REGIONS = [
        'EU' => self::EU_COUNTRIES,
        'UK' => [
            'GB',
            'IM',
        ],
    ];

    /**
     * Excluded territories are certain regions of a country (often islands)
     * that require customs, even when mailing there from the mainland.
     *
     * Each excluded region is defined by a postcode regular expression => territory name.
     *
     * @date Valid from 01/01/2021
     * @source https://ec.europa.eu/taxation_customs/business/vat/eu-vat-rules-topic/territorial-status-eu-countries-certain-territories_en
     */
    public const EXCLUDED_TERRITORIES = [
        'DE' => [
            '/^78266$/' => 'Büsingen',
        ],
        'GB' => [
            '/^bt/i' => 'Northern Ireland',
        ],
        'GR' => [
            '/^6308(6|7)$/' => 'Agio Oros (Mount Athos)',
        ],
        'IT' => [
            '/^23030$/' => 'Livigno, Sondrio',
            '/^22060$/' => 'Campione d\'Italia, Como',
        ],
        'ES' => [
            '/^3(5|8)/' => 'Canary Islands', // Las Palmas
            '/^51/'     => 'Ceuta',
            '/^52/'     => 'Melilla',
        ],
    ];

    /**
     * Checks if these two addresses requires customs info on the shipment when sending to each other
     *
     * @param string $senderCountryCode
     * @param string $recipientCountryCode
     * @param string $senderPostalCode
     * @param string $recipientPostalCode
     * @return bool
     */
    public function needsCustoms(
        string $senderCountryCode,
        string $recipientCountryCode,
        string $senderPostalCode,
        string $recipientPostalCode,
    ): bool {
        if (
            $this->isDomestic($senderCountryCode, $recipientCountryCode, $senderPostalCode, $recipientPostalCode)
            || $this->isInTheSameCustomsRegion(
                $senderCountryCode,
                $senderPostalCode,
                $recipientCountryCode,
                $recipientPostalCode,
            )
        ) {
            return false;
        }

        return true;
    }

    /**
     * @param string $senderCountryCode
     * @param string $recipientCountryCode
     * @param string $senderPostalCode
     * @param string $recipientPostalCode
     * @return bool
     */
    private function isDomestic(
        string $senderCountryCode,
        string $recipientCountryCode,
        string $senderPostalCode,
        string $recipientPostalCode,
    ): bool {
        if ($senderCountryCode !== $recipientCountryCode) {
            return false;
        }

        $senderIsInExcludedTerritory = $this->isInExcludedTerritories($senderCountryCode, $senderPostalCode);
        $recipientIsInExcludedTerritory = $this->isInExcludedTerritories($recipientCountryCode, $recipientPostalCode);

        // Both are not in excluded territory
        if (!$senderIsInExcludedTerritory && !$recipientIsInExcludedTerritory) {
            return true;
        }

        // Both are in an excluded territory.
        if ($senderIsInExcludedTerritory && $recipientIsInExcludedTerritory) {
            return $this->isTheSameExcludedTerritory(
                $senderCountryCode,
                $recipientCountryCode,
                $senderPostalCode,
                $recipientPostalCode,
            );
        }

        // The only other possibility is that either sender or recipient is in an excluded territory
        // in which case it is not a domestic shipment.
        return false;
    }

    /**
     * @param string $countryCode
     * @param string $postalCode
     * @return bool
     */
    private function isInExcludedTerritories(string $countryCode, string $postalCode): bool
    {
        // Check if address country has excluded territories.
        if (!array_key_exists($countryCode, self::EXCLUDED_TERRITORIES)) {
            return false;
        }

        return $this->getMatchingExcludedTerritory($countryCode, $postalCode) !== null;
    }

    /**
     * @param string $countryCode
     * @param string $postalCode
     * @return null|string
     */
    private function getMatchingExcludedTerritory(string $countryCode, string $postalCode): ?string
    {
        $excludedTerritories = self::EXCLUDED_TERRITORIES[$countryCode];

        foreach ($excludedTerritories as $postalCodeRegex => $territoryName) {
            $postalCode = strtoupper(preg_replace('/\s/', '', $postalCode));

            // Match the address postal code to the excluded territory regex and return territory name.
            preg_match($postalCodeRegex, $postalCode, $match);
            if (!empty($match)) {
                return $territoryName;
            }
        }

        return null;
    }

    /**
     * @param string $senderCountryCode
     * @param string $recipientCountryCode
     * @param string $senderPostalCode
     * @param string $recipientPostalCode
     * @return bool
     */
    private function isTheSameExcludedTerritory(
        string $senderCountryCode,
        string $recipientCountryCode,
        string $senderPostalCode,
        string $recipientPostalCode,
    ): bool {
        $senderExcludedTerritory = $this->getMatchingExcludedTerritory($senderCountryCode, $senderPostalCode);
        $recipientExcludedTerritory = $this->getMatchingExcludedTerritory($recipientCountryCode, $recipientPostalCode);

        if ($senderExcludedTerritory === null || $recipientExcludedTerritory === null) {
            // We can't reach this in current implementation, but it's here for completeness
            return false; // @codeCoverageIgnore
        }

        return $senderExcludedTerritory === $recipientExcludedTerritory;
    }

    /**
     * @param string $countryCode
     * @param string $postalCode
     * @return bool
     */
    public function isInEu(string $countryCode, string $postalCode): bool
    {
        if (!in_array($countryCode, self::EU_COUNTRIES)) {
            return false;
        }

        return !$this->isInExcludedTerritories($countryCode, $postalCode);
    }

    private function isInTheSameCustomsRegion(
        string $originCountryCode,
        string $originPostalCode,
        string $destinationCountryCode,
        string $destinationPostalCode,
    ): bool {
        $originCustomsRegion = $this->findCustomsRegion($originCountryCode);
        $destinationCustomsRegion = $this->findCustomsRegion($destinationCountryCode);

        if (
            $originCustomsRegion === null
            || $destinationCustomsRegion === null
            || $originCustomsRegion !== $destinationCustomsRegion
        ) {
            return false;
        }

        return !$this->isInExcludedTerritories($originCountryCode, $originPostalCode)
            && !$this->isInExcludedTerritories($destinationCountryCode, $destinationPostalCode);
    }

    private function findCustomsRegion(string $countryCode): ?string
    {
        foreach (self::CUSTOMS_REGIONS as $regionName => $countries) {
            if (in_array($countryCode, $countries)) {
                return $regionName;
            }
        }

        return null;
    }
}
