<?php

if (! function_exists('number_to_words')) {
    function number_to_words($num) {
        $ones = array(
            0 => 'Zero', 1 => 'One', 2 => 'Two', 3 => 'Three', 4 => 'Four', 5 => 'Five',
            6 => 'Six', 7 => 'Seven', 8 => 'Eight', 9 => 'Nine', 10 => 'Ten', 11 => 'Eleven',
            12 => 'Twelve', 13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen', 16 => 'Sixteen',
            17 => 'Seventeen', 18 => 'Eighteen', 19 => 'Nineteen'
        );

        $tens = array(
            2 => 'Twenty', 3 => 'Thirty', 4 => 'Forty', 5 => 'Fifty', 6 => 'Sixty',
            7 => 'Seventy', 8 => 'Eighty', 9 => 'Ninety'
        );

        $hundreds = array(
            100 => 'Hundred', 1000 => 'Thousand', 1000000 => 'Million', 1000000000 => 'Billion'
        );

        // Handle zero case
        if ($num == 0) {
            return $ones[0];
        }

        $words = '';
        $num = (int) $num;

        // Loop through larger values (hundred, thousand, million, etc.)
        foreach ($hundreds as $value => $text) {
            if ($num >= $value) {
                $quotient = (int)($num / $value); // Get the quotient (e.g., 123 / 100 = 1)
                $num %= $value; // Get the remainder (e.g., 123 % 100 = 23)

                // Convert the quotient to words
                if ($quotient < 20) {
                    $words .= $ones[$quotient]; // For numbers less than 20, directly use ones array
                } else {
                    // For numbers 20 and above, split into tens and ones
                    $tensPart = (int)($quotient / 10);
                    $onesPart = $quotient % 10;

                    // Add tens part to words if valid
                    if (isset($tens[$tensPart])) {
                        $words .= $tens[$tensPart]; // Add tens part like "Twenty", "Thirty", etc.
                    }

                    // Add ones part if valid
                    if ($onesPart > 0) {
                        $words .= ' ' . $ones[$onesPart];
                    }
                }

                // Add the appropriate scale (hundred, thousand, etc.)
                $words .= ' ' . $text . ' ';
            }
        }

        // Return the final words, trimming any extra spaces
        return trim($words);
    }
}
?>