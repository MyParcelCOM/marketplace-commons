<?php

declare(strict_types=1);

namespace Tests\Shipment\Customs;

use MyParcelCom\Integration\Shipment\Customs\CustomsChecker;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertTrue;

class CustomsCheckerTest extends TestCase
{
    public function test_needs_customs_returns_false_for_simple_domestic(): void
    {
        $customsChecker = new CustomsChecker();

        assertFalse($customsChecker->needsCustoms('NL', 'NL', '1008 DG', '1011 AW'));
    }

    public function test_needs_customs_returns_true_for_domestic_excluded_territory(): void
    {
        $customsChecker = new CustomsChecker();

        assertTrue($customsChecker->needsCustoms('NL', 'DE', '1008 DG', '78266'));
    }

    public function test_needs_customs_returns_false_for_same_excluded_territory(): void
    {
        $customsChecker = new CustomsChecker();

        assertTrue($customsChecker->needsCustoms('IT', 'IT', '23030', '22060'));
    }

    public function test_needs_customs_returns_true_for_different_excluded_territory(): void
    {
        $customsChecker = new CustomsChecker();

        assertTrue($customsChecker->needsCustoms('IT', 'IT', '23030', '22061'));
    }

    public function test_needs_customs_returns_false_for_eu_region(): void
    {
        $customsChecker = new CustomsChecker();

        assertFalse($customsChecker->needsCustoms('BE', 'NL', '3910', '1011 AW'));
    }

    public function test_is_in_eu_returns_true_if_within_eu(): void
    {
        $customsChecker = new CustomsChecker();

        assertTrue($customsChecker->isInEu('BE', '3910'));
    }

    public function test_is_in_eu_returns_false_if_outside_eu(): void
    {
        $customsChecker = new CustomsChecker();

        assertFalse($customsChecker->isInEu('GB', 'W1A 0AX'));
    }

    public function test_needs_customs_returns_true_when_needed(): void
    {
        $customsChecker = new CustomsChecker();

        assertTrue($customsChecker->needsCustoms('NL', 'GB', '1008 DG', 'W1A 0AX'));
    }

    public function test_needs_customs_returns_true_if_different_country_codes_not_in_any_customs_region(): void
    {
        $customsChecker = new CustomsChecker();

        assertTrue($customsChecker->needsCustoms('XX', 'YY', 'XXXX', 'YYYY'));
    }

    public function test_needs_customs_returns_true_if_no_matching_excluded_territories_found(): void
    {
        $customsChecker = new CustomsChecker();

        assertTrue($customsChecker->needsCustoms('XX', 'YY', 'XXXX', 'YYYY'));
    }

    public function test_it_does_not_care_about_case_in_northern_ireland_postal_code_check(): void
    {
        $customsChecker = new CustomsChecker();

        assertTrue($customsChecker->needsCustoms('GB', 'GB', 'NW1 6UX', 'bt1 5gs'));
        assertTrue($customsChecker->needsCustoms('GB', 'GB', 'NW1 6UX', 'BT1 5GS'));
    }
}
