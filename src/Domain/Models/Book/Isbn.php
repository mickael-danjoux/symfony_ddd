<?php

namespace App\Domain\Models\Book;

use App\Domain\Exception\InvalidIsbnException;

/**
 * ISBN – International Standard Book Number
 *
 * This number can be 10 or 13 format.
 * ISBN 10 example: 2-7654-1005-4
 * ISBN 13 example: 978-2-02-130451-0
 *
 * cf: https://fr.wikipedia.org/wiki/International_Standard_Book_Number
 *
 */
final class Isbn
{
    public const TOO_SHORT_ERROR = 'This value is too short';
    public const TOO_LONG_ERROR = 'This value is too long';
    public const INVALID_CHARACTERS_ERROR = 'Invalid value';
    public const CHECKSUM_FAILED_ERROR = 'Invalid value';
    public const TYPE_NOT_RECOGNIZED_ERROR = 'Not recognized value';

    private string $isbn;

    /**
     * @param string $isbn
     * @throws InvalidIsbnException
     */
    public function __construct(string $isbn)
    {

        if (!$this->isValidIsbn($isbn)) {
            throw new InvalidIsbnException("Isbn '" . $isbn . "' is not valid");
        }

        $this->isbn = $isbn;
    }

    private function isValidIsbn(string $isbn): bool
    {
        $canonical = str_replace('-', '', $isbn);
        // First, try ISBN-10
        $code = $this->validateIsbn10($canonical);
        if (Isbn::TOO_LONG_ERROR === $code) {
            // Try ISBN-13 now
            $code = $this->validateIsbn13($canonical);

            // If too short, this means we have 11 or 12 digits
            if (Isbn::TOO_SHORT_ERROR === $code) {
                $code = Isbn::TYPE_NOT_RECOGNIZED_ERROR;
            }
        }
        return $code === true;
    }

    private function validateIsbn10(string $isbn): bool|string
    {
        // Choose an algorithm so that ERROR_INVALID_CHARACTERS is preferred
        // over ERROR_TOO_SHORT/ERROR_TOO_LONG
        // Otherwise "0-45122-5244" passes, but "0-45122_5244" reports
        // "too long"

        // Error priority:
        // 1. ERROR_INVALID_CHARACTERS
        // 2. ERROR_TOO_SHORT/ERROR_TOO_LONG
        // 3. ERROR_CHECKSUM_FAILED

        $checkSum = 0;

        for ($i = 0; $i < 10; ++$i) {
            // If we test the length before the loop, we get an ERROR_TOO_SHORT
            // when actually an ERROR_INVALID_CHARACTERS is wanted, e.g. for
            // "0-45122_5244" (typo)
            if (!isset($isbn[$i])) {
                return Isbn::TOO_SHORT_ERROR;
            }

            if ('X' === $isbn[$i]) {
                $digit = 10;
            } elseif (ctype_digit($isbn[$i])) {
                $digit = $isbn[$i];
            } else {
                return Isbn::INVALID_CHARACTERS_ERROR;
            }

            $checkSum += $digit * (10 - $i);
        }

        if (isset($isbn[$i])) {
            return Isbn::TOO_LONG_ERROR;
        }

        return 0 === $checkSum % 11 ?
            true :
            Isbn::CHECKSUM_FAILED_ERROR;
    }

    protected function validateIsbn13(string $isbn): string|bool
    {
        // Error priority:
        // 1. ERROR_INVALID_CHARACTERS
        // 2. ERROR_TOO_SHORT/ERROR_TOO_LONG
        // 3. ERROR_CHECKSUM_FAILED

        if (!ctype_digit($isbn)) {
            return Isbn::INVALID_CHARACTERS_ERROR;
        }

        $length = \strlen($isbn);

        if ($length < 13) {
            return Isbn::TOO_SHORT_ERROR;
        }

        if ($length > 13) {
            return Isbn::TOO_LONG_ERROR;
        }

        $checkSum = 0;

        for ($i = 0; $i < 13; $i += 2) {
            $checkSum += (int)$isbn[$i];
        }

        for ($i = 1; $i < 12; $i += 2) {
            $checkSum += $isbn[$i] * 3;
        }

        return 0 === $checkSum % 10 ?
            true :
            Isbn::CHECKSUM_FAILED_ERROR;
    }

    public function __toString(): string
    {
        return $this->isbn;
    }

}
